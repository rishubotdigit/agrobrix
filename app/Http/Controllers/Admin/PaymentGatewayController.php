<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        $settings = [
            'razorpay_enabled' => Setting::get('razorpay_enabled', '0'),
            'razorpay_key_id' => Setting::get('razorpay_key_id', ''),
            'razorpay_key_secret' => Setting::get('razorpay_key_secret', ''),
            'razorpay_webhook_secret' => Setting::get('razorpay_webhook_secret', ''),
            'phonepe_enabled' => Setting::get('phonepe_enabled', '0'),
            'phonepe_merchant_id' => Setting::get('phonepe_merchant_id', ''),
            'phonepe_salt_key' => Setting::get('phonepe_salt_key', ''),
            'phonepe_salt_index' => Setting::get('phonepe_salt_index', '1'),
            'phonepe_webhook_secret' => Setting::get('phonepe_webhook_secret', ''),
            'upi_static_enabled' => Setting::get('upi_static_enabled', '0'),
            'upi_static_merchant_name' => Setting::get('upi_static_merchant_name', ''),
            'upi_static_upi_id' => Setting::get('upi_static_upi_id', ''),
            'upi_static_description' => Setting::get('upi_static_description', ''),
            'default_gateway' => Setting::get('default_gateway', 'razorpay'),
            'fallback_gateway' => Setting::get('fallback_gateway', 'razorpay'),
        ];

        return view('admin.payment-gateways.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'razorpay_enabled' => 'nullable|boolean',
            'razorpay_key_id' => 'nullable|string|max:255',
            'razorpay_key_secret' => 'nullable|string|max:255',
            'razorpay_webhook_secret' => 'nullable|string|max:255',
            'phonepe_enabled' => 'nullable|boolean',
            'phonepe_merchant_id' => 'nullable|string|max:255',
            'phonepe_salt_key' => 'nullable|string|max:255',
            'phonepe_salt_index' => 'nullable|string|max:10',
            'phonepe_webhook_secret' => 'nullable|string|max:255',
            'upi_static_enabled' => 'nullable|boolean',
            'upi_static_merchant_name' => 'nullable|string|max:255',
            'upi_static_upi_id' => 'nullable|string|max:255',
            'upi_static_description' => 'nullable|string|max:500',
            'default_gateway' => 'nullable|string|in:razorpay,phonepe,upi_static',
            'fallback_gateway' => 'nullable|string|in:razorpay,phonepe,upi_static',
        ]);

        Setting::set('razorpay_enabled', $request->has('razorpay_enabled') ? '1' : '0');
        Setting::set('razorpay_key_id', $request->razorpay_key_id ?? '');
        Setting::set('razorpay_key_secret', $request->razorpay_key_secret ?? '');
        Setting::set('razorpay_webhook_secret', $request->razorpay_webhook_secret ?? '');

        Setting::set('phonepe_enabled', $request->has('phonepe_enabled') ? '1' : '0');
        Setting::set('phonepe_merchant_id', $request->phonepe_merchant_id ?? '');
        Setting::set('phonepe_salt_key', $request->phonepe_salt_key ?? '');
        Setting::set('phonepe_salt_index', $request->phonepe_salt_index ?? '1');
        Setting::set('phonepe_webhook_secret', $request->phonepe_webhook_secret ?? '');

        Setting::set('upi_static_enabled', $request->has('upi_static_enabled') ? '1' : '0');
        Setting::set('upi_static_merchant_name', $request->upi_static_merchant_name ?? '');
        Setting::set('upi_static_upi_id', $request->upi_static_upi_id ?? '');
        Setting::set('upi_static_description', $request->upi_static_description ?? '');

        Setting::set('default_gateway', $request->default_gateway ?? 'razorpay');
        Setting::set('fallback_gateway', $request->fallback_gateway ?? 'razorpay');

        return redirect()->route('admin.payment-gateways.index')->with('success', 'Payment gateway settings updated successfully.');
    }
}