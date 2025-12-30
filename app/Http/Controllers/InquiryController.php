<?php

namespace App\Http\Controllers;

use App\Events\InquiryCreated;
use App\Mail\InquiryConfirmation;
use App\Models\Lead;
use App\Models\PlanPurchase;
use App\Models\Property;
use App\Models\Setting;
use App\Models\ViewedContact;
use App\OtpService;
use App\Traits\CapabilityTrait;
use App\Traits\EmailQueueTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class InquiryController extends Controller
{
    use CapabilityTrait, EmailQueueTrait;
    public function submitInquiry(Request $request, Property $property)
    {
        $rules = [
            'buyer_name' => 'required|string|max:255',
            'buyer_type' => 'required|in:agent,buyer',
            'additional_message' => 'nullable|string|max:1000',
        ];

        if (!Auth::check() || !Auth::user()->mobile) {
            $rules['buyer_phone'] = 'required|string|max:15';
        }

        if (!Auth::check() || !Auth::user()->email) {
            $rules['buyer_email'] = 'required|email|max:255';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            \Log::warning('Inquiry validation failed', [
                'user_id' => Auth::id(),
                'property_id' => $property->id,
                'errors' => $validator->errors()
            ]);
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // If user is logged in, use their email and phone if available
        $buyerEmail = Auth::check() && Auth::user()->email ? Auth::user()->email : $request->buyer_email;
        $buyerPhone = Auth::check() && Auth::user()->mobile ? Auth::user()->mobile : $request->buyer_phone;

        \Log::info('Buyer inquiry submitted successfully', [
            'user_id' => Auth::id(),
            'property_id' => $property->id,
            'buyer_email' => $buyerEmail,
            'buyer_type' => $request->buyer_type
        ]);

        // Store inquiry data in session
        Session::put('inquiry_data', [
            'property_id' => $property->id,
            'buyer_name' => $request->buyer_name,
            'buyer_email' => $buyerEmail,
            'buyer_phone' => $buyerPhone,
            'buyer_type' => $request->buyer_type,
            'additional_message' => $request->additional_message,
        ]);

        // Check if OTP is enabled
        if (Setting::get('otp_verification_enabled') == '1') {
            $otpService = new OtpService();
            $otp = $otpService->generateOtp();
            $result = $otpService->sendOtpToMobile($buyerPhone, $otp);

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send OTP. Please try again.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'otp_required' => true,
                'message' => 'Inquiry submitted. Please verify your phone number with the OTP sent.'
            ]);
        }

        // If OTP not enabled, proceed to create lead
        return $this->createLeadFromSession($property);
    }

    public function verifyOtp(Request $request, Property $property)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $otpService = new OtpService();
        if (!$otpService->verifyOtpFromSession($request->otp)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP.'
            ], 400);
        }

        return $this->createLeadFromSession($property);
    }

    private function createLeadFromSession(Property $property)
    {
        $inquiryData = Session::get('inquiry_data');

        if (!$inquiryData || $inquiryData['property_id'] != $property->id) {
            return response()->json([
                'success' => false,
                'message' => 'Inquiry data not found.'
            ], 400);
        }

        $user = Auth::user();
        $isBuyer = $user && $user->role === 'buyer' && $inquiryData['buyer_type'] === 'buyer';

        if ($isBuyer) {
            // Buyer-specific logic
            $existingViewed = ViewedContact::where('buyer_id', $user->id)
                ->where('property_id', $property->id)
                ->exists();

            if (!$existingViewed) {
                // Validate plan and credits
                $activePurchases = $user->activePlanPurchases();

                if ($activePurchases->isEmpty()) {
                    Log::error('Buyer has no active plan for inquiry', [
                        'buyer_id' => $user->id,
                        'property_id' => $property->id
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'No active plan. Please purchase a plan to view contacts.'
                    ], 403);
                }

                $maxContacts = $this->getCapabilityValue($user, 'max_contacts');
                $totalUsedContacts = $activePurchases->sum('used_contacts');

                if ($totalUsedContacts >= $maxContacts) {
                    Log::error('Buyer contact credits exhausted for inquiry', [
                        'buyer_id' => $user->id,
                        'property_id' => $property->id
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Contact credits exhausted. Please upgrade your plan.'
                    ], 403);
                }

                // Wrap deduction and creation in transaction
                DB::transaction(function () use ($user, $property, $activePurchases) {
                    $activePurchases->first()->increment('used_contacts');
                    ViewedContact::create([
                        'buyer_id' => $user->id,
                        'property_id' => $property->id,
                    ]);
                });

                Log::info('Buyer viewed contact via inquiry, credit deducted', [
                    'buyer_id' => $user->id,
                    'property_id' => $property->id
                ]);
            } else {
                Log::info('Buyer already viewed contact via inquiry, no deduction', [
                    'buyer_id' => $user->id,
                    'property_id' => $property->id
                ]);
            }
        } else {
            // Non-buyer logic: create or update Lead
            $existingLead = Lead::where('property_id', $property->id)
                ->where('buyer_email', $inquiryData['buyer_email'])
                ->first();

            if ($existingLead) {
                // Update existing lead
                $existingLead->update(array_merge($inquiryData, [
                    'agent_id' => $property->owner && $property->owner->role === 'agent' ? $property->owner_id : null,
                ]));
                Log::info('Existing lead updated from inquiry', [
                    'lead_id' => $existingLead->id,
                    'property_id' => $property->id,
                    'agent_id' => $property->agent_id,
                    'buyer_email' => $inquiryData['buyer_email']
                ]);
            } else {
                // Create new lead
                $agentId = $property->owner && $property->owner->role === 'agent' ? $property->owner_id : null;
                $lead = Lead::create(array_merge($inquiryData, [
                    'agent_id' => $agentId,
                ]));
                Log::info('New lead created from inquiry', [
                    'lead_id' => $lead->id,
                    'property_id' => $property->id,
                    'property_agent_id' => $property->agent_id,
                    'assigned_agent_id' => $agentId,
                    'property_owner_id' => $property->owner_id,
                    'property_owner_role' => $property->owner ? $property->owner->role : null,
                    'buyer_email' => $inquiryData['buyer_email']
                ]);

                // Fire the InquiryCreated event
                event(new InquiryCreated($lead));
            }
        }

        // Capture inquiry data before clearing session
        $inquiry = $inquiryData;

        // Clear session data
        Session::forget('inquiry_data');

        // Check if property has an owner
        if (!$property->owner) {
            return response()->json([
                'success' => false,
                'message' => 'Property owner information is not available.'
            ], 400);
        }

        // Return contact information and inquiry data
        return response()->json([
            'success' => true,
            'contact' => [
                'owner_name' => $property->owner->name,
                'owner_email' => $property->owner->email,
                'owner_mobile' => $property->owner->mobile,
            ],
            'inquiry' => $inquiry
        ]);
    }

    public function viewContact(Request $request, Property $property)
    {
        $user = Auth::user();

        // Check if user is the owner or agent of the property
        $isOwnerOrAgent = ($user->id === $property->owner_id) || ($user->id === $property->agent_id);

        \Log::info('viewContact called', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'property_id' => $property->id,
            'property_owner_id' => $property->owner_id,
            'property_agent_id' => $property->agent_id,
            'is_owner_or_agent' => $isOwnerOrAgent
        ]);

        if (!$isOwnerOrAgent) {
            // User is not owner/agent, apply plan limits
            if ($user->role === 'agent') {
                // Agents check their own plan limits
                $agentActivePurchases = $user->activePlanPurchases();

                \Log::info('Agent viewing contact - checking own plan', [
                    'agent_id' => $user->id,
                    'agent_active_purchases_count' => $agentActivePurchases->count(),
                    'property_owner_id' => $property->owner_id,
                    'property_id' => $property->id
                ]);

                if ($agentActivePurchases->isEmpty()) {
                    \Log::info('Agent has no active plan', ['agent_id' => $user->id]);
                    return response()->json([
                        'error' => 'No active plan. Please purchase a plan to view contacts.',
                        'redirect' => route('plans.index')
                    ], 403);
                }

                // Check max_contacts capability
                $maxContacts = $this->getCapabilityValue($user, 'max_contacts');
                $totalUsedContacts = $agentActivePurchases->sum('used_contacts');

                \Log::info('Agent contact credits check', [
                    'agent_id' => $user->id,
                    'max_contacts' => $maxContacts,
                    'total_used_contacts' => $totalUsedContacts
                ]);

                if ($totalUsedContacts >= $maxContacts) {
                    \Log::info('Agent contact credits exhausted', ['agent_id' => $user->id]);
                    return response()->json([
                        'error' => 'Contact credits exhausted. Please upgrade your plan.',
                        'redirect' => route('plans.index')
                    ], 403);
                }
            } elseif ($user->role === 'buyer') {
                // Buyers check their own plan limits
                $buyerActivePurchases = $user->activePlanPurchases();

                \Log::info('Buyer viewing contact - checking own plan', [
                    'buyer_id' => $user->id,
                    'buyer_active_purchases_count' => $buyerActivePurchases->count(),
                    'property_id' => $property->id
                ]);

                if ($buyerActivePurchases->isEmpty()) {
                    \Log::info('Buyer has no active plan', ['buyer_id' => $user->id]);
                    return response()->json([
                        'error' => 'No active plan. Please purchase a plan to view contacts.',
                        'redirect' => route('plans.index')
                    ], 403);
                }

                // Check max_contacts capability
                $maxContacts = $this->getCapabilityValue($user, 'max_contacts');
                $totalUsedContacts = $buyerActivePurchases->sum('used_contacts');

                \Log::info('Buyer contact credits check', [
                    'buyer_id' => $user->id,
                    'max_contacts' => $maxContacts,
                    'total_used_contacts' => $totalUsedContacts
                ]);

                if ($totalUsedContacts >= $maxContacts) {
                    \Log::info('Buyer contact credits exhausted', ['buyer_id' => $user->id]);
                    return response()->json([
                        'error' => 'Contact credits exhausted. Please upgrade your plan.',
                        'redirect' => route('plans.index')
                    ], 403);
                }
            } elseif ($user->role === 'admin') {
                // Admin has full access, no restrictions
                \Log::info('Admin viewing contact - allowed', [
                    'admin_id' => $user->id,
                    'property_id' => $property->id
                ]);
            } elseif ($user->role === 'owner') {
                // Owners have full access to view contacts, no restrictions
                \Log::info('Owner viewing contact - allowed', [
                    'owner_id' => $user->id,
                    'property_id' => $property->id
                ]);
            } else {
                // Other roles - deny access
                \Log::info('Unknown role viewing contact - denied', [
                    'user_id' => $user->id,
                    'role' => $user->role,
                    'property_id' => $property->id
                ]);
                return response()->json([
                    'error' => 'Access denied.'
                ], 403);
            }
        }

        // Handle lead/viewed contact creation and credit deduction based on role
        if ($user->role === 'buyer' && !$isOwnerOrAgent) {
            $existingViewed = ViewedContact::where('buyer_id', $user->id)
                ->where('property_id', $property->id)
                ->exists();

            if (!$existingViewed) {
                // Wrap deduction and creation in transaction
                DB::transaction(function () use ($user, $property) {
                    $buyerActivePurchases = $user->activePlanPurchases();
                    $buyerActivePurchases->first()->increment('used_contacts');
                    ViewedContact::create([
                        'buyer_id' => $user->id,
                        'property_id' => $property->id,
                    ]);
                });

                Log::info('Deducted contact credit from buyer in viewContact', [
                    'buyer_id' => $user->id,
                    'property_id' => $property->id
                ]);
            } else {
                Log::info('Buyer already viewed contact, no deduction', [
                    'buyer_id' => $user->id,
                    'property_id' => $property->id
                ]);
            }
        } elseif ($user->role === 'agent' && !$isOwnerOrAgent) {
            $existingLead = Lead::where('property_id', $property->id)
                ->where('buyer_name', $user->name)
                ->where('buyer_email', $user->email)
                ->exists();

            if (!$existingLead) {
                // Create lead for assigned agent or owner
                $agentId = $property->owner && $property->owner->role === 'agent' ? $property->owner_id : null;

                $lead = Lead::create([
                    'property_id' => $property->id,
                    'agent_id' => $agentId,
                    'buyer_name' => $user->name,
                    'buyer_email' => $user->email,
                    'buyer_phone' => $user->mobile ?? '',
                ]);

                Log::info('Lead created from view contact for agent', [
                    'lead_id' => $lead->id,
                    'property_id' => $property->id,
                    'property_agent_id' => $property->agent_id,
                    'assigned_agent_id' => $agentId,
                    'property_owner_id' => $property->owner_id,
                    'property_owner_role' => $property->owner ? $property->owner->role : null,
                    'buyer_email' => $user->email
                ]);

                // Deduct credit
                $agentActivePurchases = $user->activePlanPurchases();
                $agentActivePurchases->first()->increment('used_contacts');
                Log::info('Deducted contact credit from agent', [
                    'agent_id' => $user->id,
                    'property_id' => $property->id
                ]);
            }
        }

        // Check if property has an owner
        if (!$property->owner) {
            return response()->json([
                'error' => 'Property owner information is not available.'
            ], 400);
        }

        // Return contact information
        return response()->json([
            'contact' => [
                'contact_name' => $property->contact_name,
                'contact_mobile' => $property->contact_mobile,
            ]
        ]);
    }

    public function checkInquiryStatus(Request $request, Property $property)
    {
        if (!Auth::check() || Auth::user()->role !== 'buyer') {
            return response()->json(['exists' => false]);
        }

        $user = Auth::user();
        $exists = ViewedContact::where('buyer_id', $user->id)
            ->where('property_id', $property->id)
            ->exists();

        if ($exists) {
            return response()->json(['exists' => true]);
        }

        // Check active plan and credits
        $activePurchases = $user->activePlanPurchases();

        if ($activePurchases->isEmpty()) {
            return response()->json([
                'exists' => false,
                'can_inquire' => false,
                'message' => 'No active plan. Please purchase a plan to inquire.'
            ]);
        }

        $maxContacts = $this->getCapabilityValue($user, 'max_contacts');
        $totalUsedContacts = $activePurchases->sum('used_contacts');

        if ($totalUsedContacts >= $maxContacts) {
            return response()->json([
                'exists' => false,
                'can_inquire' => false,
                'message' => 'Contact credits exhausted. Please upgrade your plan.'
            ]);
        }

        return response()->json([
            'exists' => false,
            'can_inquire' => true
        ]);
    }
}