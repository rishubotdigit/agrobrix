<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'login_enabled' => Setting::get('login_enabled', '1'),
            'registration_enabled' => Setting::get('registration_enabled', '1'),
            'otp_verification_enabled' => Setting::get('otp_verification_enabled', '1'),
            'mobile_integration_enabled' => Setting::get('mobile_integration_enabled', '1'),
            'whatsapp_notifications_enabled' => Setting::get('whatsapp_notifications_enabled', '1'),
            'queue_mode' => Setting::get('queue_mode', 'disabled'),
            'user_registration_welcome_email_enabled' => Setting::get('user_registration_welcome_email_enabled', '1'),
            'admin_new_user_notification_enabled' => Setting::get('admin_new_user_notification_enabled', '1'),
            'user_plan_purchase_confirmation_enabled' => Setting::get('user_plan_purchase_confirmation_enabled', '1'),
            'admin_plan_purchase_notification_enabled' => Setting::get('admin_plan_purchase_notification_enabled', '1'),
            'property_approval_email_enabled' => Setting::get('property_approval_email_enabled', '1'),
            'property_rejection_email_enabled' => Setting::get('property_rejection_email_enabled', '1'),
            'payment_approved_email_enabled' => Setting::get('payment_approved_email_enabled', '1'),
            'payment_rejected_email_enabled' => Setting::get('payment_rejected_email_enabled', '1'),
            'invoice_email_enabled' => Setting::get('invoice_email_enabled', '1'),
            'admin_payment_submitted_notification_enabled' => Setting::get('admin_payment_submitted_notification_enabled', '1'),
            'admin_property_submitted_notification_enabled' => Setting::get('admin_property_submitted_notification_enabled', '1'),
            'admin_property_approved_notification_enabled' => Setting::get('admin_property_approved_notification_enabled', '1'),
            'admin_property_rejected_notification_enabled' => Setting::get('admin_property_rejected_notification_enabled', '1'),
            'admin_payment_approved_notification_enabled' => Setting::get('admin_payment_approved_notification_enabled', '1'),
            'google_maps_api_key' => Setting::get('google_maps_api_key', ''),
            'map_enabled' => Setting::get('map_enabled', '1'),
            'google_login_enabled' => Setting::get('google_login_enabled', '0'),
            'facebook_login_enabled' => Setting::get('facebook_login_enabled', '0'),
            'smtp_host' => Setting::get('smtp_host', ''),
            'smtp_port' => Setting::get('smtp_port', '587'),
            'smtp_email' => Setting::get('smtp_email', ''),
            'smtp_password' => Setting::get('smtp_password', ''),
            'smtp_encryption' => Setting::get('smtp_encryption', 'tls'),
            'smtp_from_name' => Setting::get('smtp_from_name', ''),
            'logo' => Setting::get('logo', ''),
            'favicon' => Setting::get('favicon', ''),
            'homepage_states' => Setting::get('homepage_states', json_encode(['Punjab', 'Haryana', 'Chandigarh', 'Himachal Pradesh'])),
            'homepage_properties' => Setting::get('homepage_properties', '[]'),
        ];

        $allProperties = \App\Models\Property::where('status', 'approved')
            ->select('id', 'title', 'state', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        Log::info('Settings index: queue_mode retrieved as ' . $settings['queue_mode']);

        return view('admin.settings.index', compact('settings', 'allProperties'));
    }

    public function update(Request $request)
    {
        Log::info('Settings update: request data', $request->all());

        $request->validate([
            'login_enabled' => 'nullable|boolean',
            'registration_enabled' => 'nullable|boolean',
            'otp_verification_enabled' => 'nullable|boolean',
            'mobile_integration_enabled' => 'nullable|boolean',
            'whatsapp_notifications_enabled' => 'nullable|boolean',
            'queue_mode' => 'nullable|in:enabled,disabled',
            'user_registration_welcome_email_enabled' => 'nullable|boolean',
            'admin_new_user_notification_enabled' => 'nullable|boolean',
            'user_plan_purchase_confirmation_enabled' => 'nullable|boolean',
            'admin_plan_purchase_notification_enabled' => 'nullable|boolean',
            'property_approval_email_enabled' => 'nullable|boolean',
            'property_rejection_email_enabled' => 'nullable|boolean',
            'payment_approved_email_enabled' => 'nullable|boolean',
            'payment_rejected_email_enabled' => 'nullable|boolean',
            'invoice_email_enabled' => 'nullable|boolean',
            'admin_payment_submitted_notification_enabled' => 'nullable|boolean',
            'admin_property_submitted_notification_enabled' => 'nullable|boolean',
            'admin_property_approved_notification_enabled' => 'nullable|boolean',
            'admin_property_rejected_notification_enabled' => 'nullable|boolean',
            'admin_payment_approved_notification_enabled' => 'nullable|boolean',
            'google_maps_api_key' => 'nullable|string|max:255',
            'map_enabled' => 'nullable|boolean',
            'google_login_enabled' => 'nullable|boolean',
            'facebook_login_enabled' => 'nullable|boolean',
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
            'smtp_email' => 'nullable|email|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_encryption' => 'nullable|in:tls,ssl',
            'smtp_from_name' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico|max:1024',
            'homepage_states' => 'nullable|array',

            'homepage_states.*' => 'string|max:100',
            'homepage_properties' => 'nullable|array',
            'homepage_properties.*' => 'integer',
        ]);

        Setting::set('login_enabled', $request->has('login_enabled') ? '1' : '0');
        Setting::set('registration_enabled', $request->has('registration_enabled') ? '1' : '0');
        Setting::set('otp_verification_enabled', $request->has('otp_verification_enabled') ? '1' : '0');
        Setting::set('mobile_integration_enabled', $request->has('mobile_integration_enabled') ? '1' : '0');
        Setting::set('whatsapp_notifications_enabled', $request->has('whatsapp_notifications_enabled') ? '1' : '0');
        Setting::set('queue_mode', $request->input('queue_mode', 'disabled'));
        Setting::set('user_registration_welcome_email_enabled', $request->has('user_registration_welcome_email_enabled') ? '1' : '0');
        Setting::set('admin_new_user_notification_enabled', $request->has('admin_new_user_notification_enabled') ? '1' : '0');
        Setting::set('user_plan_purchase_confirmation_enabled', $request->has('user_plan_purchase_confirmation_enabled') ? '1' : '0');
        Setting::set('admin_plan_purchase_notification_enabled', $request->has('admin_plan_purchase_notification_enabled') ? '1' : '0');
        Setting::set('property_approval_email_enabled', $request->has('property_approval_email_enabled') ? '1' : '0');
        Setting::set('property_rejection_email_enabled', $request->has('property_rejection_email_enabled') ? '1' : '0');
        Setting::set('payment_approved_email_enabled', $request->has('payment_approved_email_enabled') ? '1' : '0');
        Setting::set('payment_rejected_email_enabled', $request->has('payment_rejected_email_enabled') ? '1' : '0');
        Setting::set('invoice_email_enabled', $request->has('invoice_email_enabled') ? '1' : '0');
        Setting::set('admin_payment_submitted_notification_enabled', $request->has('admin_payment_submitted_notification_enabled') ? '1' : '0');
        Setting::set('admin_property_submitted_notification_enabled', $request->has('admin_property_submitted_notification_enabled') ? '1' : '0');
        Setting::set('admin_property_approved_notification_enabled', $request->has('admin_property_approved_notification_enabled') ? '1' : '0');
        Setting::set('admin_property_rejected_notification_enabled', $request->has('admin_property_rejected_notification_enabled') ? '1' : '0');
        Setting::set('admin_payment_approved_notification_enabled', $request->has('admin_payment_approved_notification_enabled') ? '1' : '0');
        Setting::set('google_maps_api_key', $request->input('google_maps_api_key', ''));
        Setting::set('map_enabled', $request->has('map_enabled') ? '1' : '0');
        Setting::set('google_login_enabled', $request->has('google_login_enabled') ? '1' : '0');
        Setting::set('facebook_login_enabled', $request->has('facebook_login_enabled') ? '1' : '0');
        Setting::set('smtp_host', $request->input('smtp_host', ''));
        Setting::set('smtp_port', $request->input('smtp_port', '587'));
        Setting::set('smtp_email', $request->input('smtp_email', ''));
        Setting::set('smtp_password', $request->input('smtp_password', ''));
        Setting::set('smtp_encryption', $request->input('smtp_encryption', 'tls'));
        Setting::set('smtp_from_name', $request->input('smtp_from_name', ''));
        
        // Save homepage states
        if ($request->has('homepage_states')) {
            Setting::set('homepage_states', json_encode($request->input('homepage_states')));
        }
        
        Setting::set('homepage_properties', json_encode($request->input('homepage_properties', [])));

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('images', 'public');
            Setting::set('logo', 'storage/' . $logoPath);
        }

        if ($request->hasFile('favicon')) {
            $faviconPath = $request->file('favicon')->store('', 'public');
            Setting::set('favicon', 'storage/' . $faviconPath);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }

    public function deleteLogo()
    {
        $currentLogo = Setting::get('logo');
        
        if ($currentLogo) {
            // Remove 'storage/' prefix if it exists
            $logoPath = str_replace('storage/', '', $currentLogo);
            
            // Delete the file from storage
            if (Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }
            
            // Clear the setting
            Setting::set('logo', '');
            
            return redirect()->route('admin.settings.index')->with('success', 'Logo deleted successfully. Text branding will be used.');
        }
        
        return redirect()->route('admin.settings.index')->with('info', 'No logo to delete.');
    }

    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        $smtpHost = Setting::get('smtp_host');
        $smtpPort = Setting::get('smtp_port');
        $smtpEmail = Setting::get('smtp_email');
        $smtpPassword = Setting::get('smtp_password');
        $smtpEncryption = Setting::get('smtp_encryption');
        $smtpFromName = Setting::get('smtp_from_name');

        if (!$smtpHost || !$smtpEmail || !$smtpPassword) {
            return response()->json(['success' => false, 'message' => 'SMTP settings are incomplete. Please configure all required fields.']);
        }

        try {
            Log::info('Test email attempt: Default mailer before config: ' . config('mail.default'));
            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.host' => $smtpHost,
                'mail.mailers.smtp.port' => $smtpPort,
                'mail.mailers.smtp.username' => $smtpEmail,
                'mail.mailers.smtp.password' => $smtpPassword,
                'mail.mailers.smtp.encryption' => $smtpEncryption,
                'mail.mailers.smtp.verify_peer' => false,
                'mail.mailers.smtp.verify_peer_name' => false,
                'mail.from.address' => $smtpEmail,
                'mail.from.name' => $smtpFromName ?: config('app.name'),
            ]);
            Log::info('Test email attempt: SMTP config set - host: ' . $smtpHost . ', port: ' . $smtpPort . ', email: ' . $smtpEmail);
            Log::info('Test email attempt: Default mailer after config: ' . config('mail.default'));

            Mail::raw('This is a test email from your SMTP configuration.', function ($message) use ($request, $smtpEmail, $smtpFromName) {
                $message->to($request->test_email)
                        ->subject('SMTP Test Email')
                        ->from($smtpEmail, $smtpFromName ?: config('app.name'));
            });

            Log::info('Test email sent successfully to: ' . $request->test_email);
            return response()->json(['success' => true, 'message' => 'Test email sent successfully!']);
        } catch (\Swift_TransportException $e) {
            Log::error('SMTP test email failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'SMTP connection failed: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            Log::error('Test email failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to send test email: ' . $e->getMessage()]);
        }
    }
}
