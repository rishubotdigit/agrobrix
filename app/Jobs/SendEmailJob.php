<?php

namespace App\Jobs;

use App\Models\EmailLog;
use App\Traits\DynamicSmtpTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendEmailJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [10, 30, 60]; // Exponential backoff in seconds
    public $timeout = 300; // 5 minutes timeout to match SendBulkEmailJob

    protected $mailable;
    protected $recipientEmail;
    protected $userId;
    protected $modelType;
    protected $modelId;
    protected $emailType;

    /**
     * Create a new job instance.
     */
    public function __construct($mailable, string $recipientEmail, ?int $userId = null, ?string $modelType = null, ?int $modelId = null, string $emailType = 'general')
    {
        $this->mailable = $mailable;
        $this->recipientEmail = $recipientEmail;
        $this->userId = $userId;
        $this->modelType = $modelType;
        $this->modelId = $modelId;
        $this->emailType = $emailType;
        $this->onQueue('emails');
    }

    /**
     * Get the unique identifier for the job.
     */
    public function uniqueId(): string
    {
        return md5($this->recipientEmail . get_class($this->mailable) . ($this->userId ?? ''));
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \Log::info('SendEmailJob: Starting job execution', [
            'recipient' => $this->recipientEmail,
            'email_type' => $this->emailType,
            'model_type' => $this->modelType,
            'model_id' => $this->modelId,
        ]);

        // Check if email already sent with database locking
        $existingLog = DB::transaction(function () {
            return EmailLog::where('status', 'sent')
                ->where('email_type', $this->emailType)
                ->where('recipient_email', $this->recipientEmail)
                ->where('model_type', $this->modelType)
                ->where('model_id', $this->modelId)
                ->lockForUpdate()
                ->first();
        });

        \Log::info('SendEmailJob: Checked for existing sent log', [
            'recipient' => $this->recipientEmail,
            'email_type' => $this->emailType,
            'model_type' => $this->modelType,
            'model_id' => $this->modelId,
            'existing_log_found' => $existingLog ? true : false,
            'existing_log_id' => $existingLog ? $existingLog->id : null,
        ]);

        if ($existingLog) {
            \Log::info('SendEmailJob: Email skipped - already sent', [
                'recipient' => $this->recipientEmail,
                'email_type' => $this->emailType,
                'model_type' => $this->modelType,
                'model_id' => $this->modelId,
                'existing_log_id' => $existingLog->id,
                'existing_sent_at' => $existingLog->sent_at,
            ]);
            return;
        }

        \Log::info('SendEmailJob: No existing sent log found, proceeding to send', [
            'recipient' => $this->recipientEmail,
            'email_type' => $this->emailType,
            'model_type' => $this->modelType,
            'model_id' => $this->modelId,
        ]);

        // Load dynamic SMTP settings
        DynamicSmtpTrait::loadSmtpSettings();

        // Log SMTP settings for debugging
        \Log::info('SendEmailJob: SMTP settings loaded', [
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'username' => config('mail.mailers.smtp.username') ? 'set' : 'not set',
            'password' => config('mail.mailers.smtp.password') ? 'set' : 'not set',
            'encryption' => config('mail.mailers.smtp.encryption'),
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
            'default_mailer' => config('mail.default'),
            'recipient' => $this->recipientEmail,
            'email_type' => $this->emailType,
        ]);

        try {
            \Log::info('SendEmailJob: Attempting to send email', [
                'recipient' => $this->recipientEmail,
                'email_type' => $this->emailType,
            ]);

            // Send the email
            try {
                // Send the actual mailable
                Mail::to($this->recipientEmail)->send($this->mailable);
            } catch (\Exception $e) {
                \Log::error('SendEmailJob: Mail send failed', [
                    'recipient' => $this->recipientEmail,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                throw $e;
            }

            \Log::info('SendEmailJob: Email sent successfully', [
                'recipient' => $this->recipientEmail,
                'email_type' => $this->emailType,
            ]);

            // Log successful send
            $logData = [
                'email_type' => $this->emailType,
                'recipient_email' => $this->recipientEmail,
                'user_id' => $this->userId,
                'model_type' => $this->modelType,
                'model_id' => $this->modelId,
                'status' => 'sent',
                'sent_at' => now(),
            ];

            // Ensure user_id is valid or null
            if ($this->userId && !\App\Models\User::find($this->userId)) {
                $logData['user_id'] = null;
                \Log::warning('SendEmailJob: User does not exist, setting user_id to null', [
                    'original_user_id' => $this->userId,
                ]);
            }

            try {
                $emailLog = EmailLog::create($logData);
                \Log::info('SendEmailJob: EmailLog created', ['id' => $emailLog->id]);
            } catch (\Exception $e) {
                \Log::error('SendEmailJob: Failed to create EmailLog', [
                    'error' => $e->getMessage(),
                    'data' => $logData,
                ]);
                // Do not throw, as email was sent successfully
            }

        } catch (Throwable $e) {
            \Log::error('SendEmailJob: Email send failed', [
                'recipient' => $this->recipientEmail,
                'email_type' => $this->emailType,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Log the failure
            $logData = [
                'email_type' => $this->emailType,
                'recipient_email' => $this->recipientEmail,
                'user_id' => $this->userId,
                'model_type' => $this->modelType,
                'model_id' => $this->modelId,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ];

            try {
                EmailLog::create($logData);
            } catch (\Exception $logException) {
                \Log::error('SendEmailJob: Failed to create failure EmailLog', [
                    'error' => $logException->getMessage(),
                    'data' => $logData,
                ]);
                // Continue to throw the original exception
            }

            // Re-throw to trigger retry or failure
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        // Additional logging or notification can be added here
        \Log::error('SendEmailJob failed permanently', [
            'email_type' => $this->emailType,
            'recipient' => $this->recipientEmail,
            'error' => $exception->getMessage(),
        ]);
    }
}