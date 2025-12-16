<?php

namespace App\Traits;

use App\Models\Setting;

trait DynamicSmtpTrait
{
    /**
     * Load SMTP settings from database and configure mail dynamically.
     */
    public static function loadSmtpSettings(): void
    {
        $smtpHost = Setting::get('smtp_host');

        if ($smtpHost) {
            // Set default mailer to SMTP if database has SMTP host configured
            config(['mail.default' => 'smtp']);

            // Configure SMTP mailer with database settings, falling back to .env
            config(['mail.mailers.smtp.host' => $smtpHost]);
            config(['mail.mailers.smtp.port' => Setting::get('smtp_port', env('MAIL_PORT', 587))]);
            config(['mail.mailers.smtp.username' => Setting::get('smtp_email', env('MAIL_USERNAME'))]);
            config(['mail.mailers.smtp.password' => Setting::get('smtp_password', env('MAIL_PASSWORD'))]);
            config(['mail.mailers.smtp.encryption' => Setting::get('smtp_encryption', env('MAIL_ENCRYPTION', 'tls'))]);
            config(['mail.mailers.smtp.verify_peer' => false, 'mail.mailers.smtp.verify_peer_name' => false]);

            // Configure global from address
            $fromEmail = Setting::get('smtp_email', env('MAIL_FROM_ADDRESS', 'hello@example.com'));
            $fromName = Setting::get('smtp_from_name', env('MAIL_FROM_NAME', 'Example'));
            config(['mail.from.address' => $fromEmail]);
            config(['mail.from.name' => $fromName]);
        }
        // If no SMTP host in database, use default config (which uses .env)
    }

    /**
     * Build the message. This is called before sending.
     */
    public function build()
    {
        $this->loadSmtpSettings();

        return $this;
    }
}