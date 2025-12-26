<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use App\Models\User;
use App\Traits\DynamicSmtpTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailLogController extends Controller
{
    public function index(Request $request)
    {
        \Log::info('EmailLogController index called', [
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role ?? null,
            'request_params' => $request->all()
        ]);

        $query = EmailLog::with('user')->orderBy('created_at', 'desc');

        // Filter by email type
        if ($request->filled('email_type')) {
            $query->where('email_type', $request->email_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $emailLogs = $query->paginate(20);

        \Log::info('EmailLogController query executed', [
            'total_logs' => $emailLogs->total(),
            'current_page' => $emailLogs->currentPage(),
            'per_page' => $emailLogs->perPage(),
            'filters' => [
                'email_type' => $request->email_type,
                'status' => $request->status,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
            ]
        ]);

        return view('admin.email-logs.index', compact('emailLogs'));
    }

    public function resend(Request $request, $id)
    {
        $emailLog = EmailLog::findOrFail($id);
        Log::info("Resending email log ID: {$id}, model_type: {$emailLog->model_type}, model_id: {$emailLog->model_id}");
        if (!$emailLog->model_type || !is_string($emailLog->model_type) || !class_exists($emailLog->model_type)) {
            Log::error("Invalid model type for email log ID: {$id}");
            return redirect()->back()->with('error', 'Invalid model type for this email log.');
        }

        if (!$emailLog->model_id) {
            Log::error("Invalid model id for email log ID: {$id}");
            return redirect()->back()->with('error', 'Invalid model ID for this email log.');
        }

        $model = $emailLog->model_type::find($emailLog->model_id);
        if (!$model) {
            Log::error("Related model not found for email log ID: {$id}, model_type: {$emailLog->model_type}, model_id: {$emailLog->model_id}");
            return redirect()->back()->with('error', 'The related record has been deleted, email cannot be resent.');
        }
        Log::info("Related model found for email log ID: {$id}");

        $mailClass = $this->getMailClass($emailLog->email_type);
        if (!$mailClass) {
            return response()->json(['error' => 'Unknown email type'], 400);
        }

        DynamicSmtpTrait::loadSmtpSettings();

        try {
            if ($emailLog->recipient_email === 'admins') {
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    Mail::to($admin->email)->send(new $mailClass($model));
                }
            } else {
                Mail::to($emailLog->recipient_email)->send(new $mailClass($model));
            }

            $emailLog->update([
                'status' => 'resent',
                'sent_at' => now(),
                'error_message' => null,
            ]);

            return redirect()->route('admin.email-logs.index')->with('success', 'Email resent successfully');
        } catch (\Exception $e) {
            $emailLog->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return redirect()->route('admin.email-logs.index')->with('error', 'Failed to resend email: ' . $e->getMessage());
        }
    }

    private function getMailClass($emailType)
    {
        $mappings = [
            'payment_approved' => \App\Mail\PaymentApproved::class,
            'notify_admin_payment_approved' => \App\Mail\NotifyAdminPaymentApproved::class,
            'notify_admin_property_approved' => \App\Mail\NotifyAdminPropertyApproved::class,
            'property_rejected' => \App\Mail\PropertyRejected::class,
            'notify_admin_payment_submitted' => \App\Mail\NotifyAdminPaymentSubmitted::class,
            'invoice' => \App\Mail\InvoiceEmail::class,
            'notify_admin_plan_purchase_approval_needed' => \App\Mail\NotifyAdminPlanPurchase::class,
            'welcome_user' => \App\Mail\WelcomeUser::class,
            'notify_admin_new_user' => \App\Mail\NotifyAdminNewUser::class,
            'property_approved' => \App\Mail\PropertyApproved::class,
            'notify_admin_property_submitted' => \App\Mail\NotifyAdminPropertySubmitted::class,
        ];

        return $mappings[$emailType] ?? null;
    }
}
