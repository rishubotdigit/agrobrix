<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;

class SmsGatewayController extends Controller
{
    public function index()
    {
        $settings = [
            'sms_gateway' => Setting::get('sms_gateway', 'twilio'),
            '2factor_api_key' => Setting::get('2factor_api_key', ''),
            'msg91_authkey' => Setting::get('msg91_authkey', ''),
            'sender_id' => Setting::get('sender_id', ''),
            'template_id' => Setting::get('template_id', ''),
            'entity_id' => Setting::get('entity_id', ''),
            'otp_expiry_time' => Setting::get('otp_expiry_time', '5'),
            'otp_resend_limit' => Setting::get('otp_resend_limit', '3'),
        ];

        $templates = SmsTemplate::orderBy('created_at', 'desc')->get();
        $templateTypes = SmsTemplate::getTemplateTypes();

        return view('admin.sms-gateways.index', compact('settings', 'templates', 'templateTypes'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'msg91_authkey' => 'required|string',
            'otp_expiry_time' => 'nullable|integer|min:1',
            'otp_resend_limit' => 'nullable|integer|min:1',
        ]);

        // Always set gateway to MSG91
        Setting::set('sms_gateway', 'msg91');
        Setting::set('msg91_authkey', $request->input('msg91_authkey'));
        Setting::set('otp_expiry_time', $request->input('otp_expiry_time', '5'));
        Setting::set('otp_resend_limit', $request->input('otp_resend_limit', '3'));

        return redirect()->route('admin.sms-gateways.index')->with('success', 'MSG91 settings updated successfully.');
    }

    /**
     * Store a new SMS template
     */
    public function storeTemplate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:sms_templates,slug',
            'template_id' => 'required|string|max:255',
            'gateway' => 'required|in:msg91,2factor,twilio',
            'description' => 'nullable|string',
            'variables' => 'nullable|array',
            'is_active' => 'nullable|boolean',
        ]);

        SmsTemplate::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'template_id' => $request->template_id,
            'gateway' => $request->gateway,
            'description' => $request->description,
            'variables' => $request->variables ?? [],
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.sms-gateways.index')->with('success', 'SMS template created successfully.');
    }

    /**
     * Update an existing SMS template
     */
    public function updateTemplate(Request $request, SmsTemplate $template)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:sms_templates,slug,' . $template->id,
            'template_id' => 'required|string|max:255',
            'gateway' => 'required|in:msg91,2factor,twilio',
            'description' => 'nullable|string',
            'variables' => 'nullable|array',
            'is_active' => 'nullable|boolean',
        ]);

        $template->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'template_id' => $request->template_id,
            'gateway' => $request->gateway,
            'description' => $request->description,
            'variables' => $request->variables ?? [],
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.sms-gateways.index')->with('success', 'SMS template updated successfully.');
    }

    /**
     * Test an SMS template
     */
    public function testTemplate(Request $request, SmsTemplate $template)
    {
        $request->validate([
            'mobile' => 'required|string|regex:/^[0-9]{10,15}$/',
        ]);

        $otpService = app(\App\OtpService::class);
        $testOtp = rand(100000, 999999);

        $result = $otpService->sendOtpMsg91($request->mobile, $testOtp, $template->slug);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Test SMS sent successfully to ' . $request->mobile,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test SMS: ' . ($result['error'] ?? 'Unknown error'),
            ], 400);
        }
    }

    /**
     * Delete an SMS template
     */
    public function deleteTemplate(SmsTemplate $template)
    {
        $template->delete();

        return redirect()->route('admin.sms-gateways.index')->with('success', 'SMS template deleted successfully.');
    }
}