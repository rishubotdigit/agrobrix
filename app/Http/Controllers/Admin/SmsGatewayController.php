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
            'enable_2factor' => 'nullable|boolean',
            'enable_msg91' => 'nullable|boolean',
            '2factor_api_key' => 'required_if:enable_2factor,1',
            'msg91_authkey' => 'required_if:enable_msg91,1',
            'sender_id' => 'required_if:enable_2factor,1|required_if:enable_msg91,1',
            'template_id' => 'required_if:enable_2factor,1',
            'entity_id' => 'required_if:enable_2factor,1',
            'otp_expiry_time' => 'nullable|integer|min:1',
            'otp_resend_limit' => 'nullable|integer|min:1',
        ]);

        // Determine which gateway is enabled
        $sms_gateway = '';
        if ($request->has('enable_2factor')) {
            $sms_gateway = '2factor';
        } elseif ($request->has('enable_msg91')) {
            $sms_gateway = 'msg91';
        }

        Setting::set('sms_gateway', $sms_gateway);
        Setting::set('2factor_api_key', $request->input('2factor_api_key'));
        Setting::set('msg91_authkey', $request->input('msg91_authkey'));
        Setting::set('sender_id', $request->input('sender_id'));
        Setting::set('template_id', $request->input('template_id'));
        Setting::set('entity_id', $request->input('entity_id'));
        Setting::set('otp_expiry_time', $request->input('otp_expiry_time', '5'));
        Setting::set('otp_resend_limit', $request->input('otp_resend_limit', '3'));

        return redirect()->route('admin.sms-gateways.index')->with('success', 'SMS Gateway settings updated successfully.');
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
     * Delete an SMS template
     */
    public function deleteTemplate(SmsTemplate $template)
    {
        $template->delete();

        return redirect()->route('admin.sms-gateways.index')->with('success', 'SMS template deleted successfully.');
    }
}