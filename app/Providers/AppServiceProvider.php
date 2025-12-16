<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureMailSettings();
    }

    /**
     * Configure mail settings dynamically from database with .env fallback.
     */
    private function configureMailSettings(): void
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

            // Configure global from address
            $fromEmail = Setting::get('smtp_email', env('MAIL_FROM_ADDRESS', 'hello@example.com'));
            $fromName = Setting::get('smtp_from_name', env('MAIL_FROM_NAME', 'Example'));
            config(['mail.from.address' => $fromEmail]);
            config(['mail.from.name' => $fromName]);
        }
        // If no SMTP host in database, use default config (which uses .env)
    }
}
