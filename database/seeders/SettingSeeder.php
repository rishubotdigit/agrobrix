<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Google OAuth Settings
        Setting::set('google_client_id', '44703764490-e775j96fme92avvdmdecmnjl80qne2bc.apps.googleusercontent.com');
        Setting::set('google_client_secret', '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');
        Setting::set('google_redirect_uri', '/auth/google/callback');

        // Default settings
        Setting::set('login_enabled', '1');
        Setting::set('registration_enabled', '1');
        Setting::set('otp_verification_enabled', '1');
        Setting::set('whatsapp_notifications_enabled', '1');
        Setting::set('queue_mode', 'disabled');
        Setting::set('user_registration_welcome_email_enabled', '1');
        Setting::set('admin_new_user_notification_enabled', '1');
        Setting::set('user_plan_purchase_confirmation_enabled', '1');
        Setting::set('admin_plan_purchase_notification_enabled', '1');
        Setting::set('property_approval_email_enabled', '1');
        Setting::set('property_rejection_email_enabled', '1');
        Setting::set('payment_approved_email_enabled', '1');
        Setting::set('payment_rejected_email_enabled', '1');
        Setting::set('invoice_email_enabled', '1');
        Setting::set('admin_payment_submitted_notification_enabled', '1');
        Setting::set('admin_property_submitted_notification_enabled', '1');
        Setting::set('admin_property_approved_notification_enabled', '1');
        Setting::set('admin_property_rejected_notification_enabled', '1');
        Setting::set('admin_payment_approved_notification_enabled', '1');
        Setting::set('map_enabled', '1');
        Setting::set('google_login_enabled', '0');
        Setting::set('facebook_login_enabled', '0');
    }
}