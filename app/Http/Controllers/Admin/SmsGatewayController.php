<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SmsGatewayController extends Controller
{
    public function index()
    {
        $settings = [
            'sms_gateway' => Setting::get('sms_gateway', 'twilio'),
            '2factor_api_key' => Setting::get('2factor_api_key', ''),
            'sender_id' => Setting::get('sender_id', ''),
            'template_id' => Setting::get('template_id', ''),
            'entity_id' => Setting::get('entity_id', ''),
            'otp_expiry_time' => Setting::get('otp_expiry_time', '5'),
            'otp_resend_limit' => Setting::get('otp_resend_limit', '3'),
        ];

        return view('admin.sms-gateways.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'enable_2factor' => 'nullable|boolean',
            '2factor_api_key' => 'required_if:enable_2factor,1',
            'sender_id' => 'required_if:enable_2factor,1',
            'template_id' => 'required_if:enable_2factor,1',
            'entity_id' => 'required_if:enable_2factor,1',
            'otp_expiry_time' => 'nullable|integer|min:1',
            'otp_resend_limit' => 'nullable|integer|min:1',
        ]);

        $sms_gateway = $request->has('enable_2factor') ? '2factor' : '';

        Setting::set('sms_gateway', $sms_gateway);
        Setting::set('2factor_api_key', $request->input('2factor_api_key'));
        Setting::set('sender_id', $request->input('sender_id'));
        Setting::set('template_id', $request->input('template_id'));
        Setting::set('entity_id', $request->input('entity_id'));
        Setting::set('otp_expiry_time', $request->input('otp_expiry_time', '5'));
        Setting::set('otp_resend_limit', $request->input('otp_resend_limit', '3'));

        return redirect()->route('admin.sms-gateways.index')->with('success', 'SMS Gateway settings updated successfully.');
    }
}