<?php

namespace App\Jobs;

use App\Models\EmailLog;
use App\Traits\DynamicSmtpTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendBulkEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [10, 30, 60];
    public $timeout = 300; // 5 minutes timeout for bulk operations
    public $queue; // Use dedicated notifications queue

    protected $mailable;
    protected $recipients;
    protected $emailType;

    /**
     * Create a new job instance.
     *
     * @param mixed $mailable The mailable instance
     * @param array $recipients Array of recipient data: [['email' => '...', 'user_id' => ..., 'model_type' => '...', 'model_id' => ...], ...]
     * @param string $emailType Type of email for logging
     */
    public function __construct($mailable, array $recipients, string $emailType = 'bulk')
    {
        $this->mailable = $mailable;
        $this->recipients = $recipients;
        $this->emailType = $emailType;
        $this->queue = 'notifications'; // Set the queue for this job
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Load dynamic SMTP settings once
        DynamicSmtpTrait::loadSmtpSettings();

        $batchSize = 10; // Process in batches to avoid memory issues
        $batches = array_chunk($this->recipients, $batchSize);

        foreach ($batches as $batch) {
            foreach ($batch as $recipient) {
                $this->sendToRecipient($recipient);
            }

            // Small delay between batches to prevent overwhelming SMTP server
            sleep(1);
        }
    }

    /**
     * Send email to a single recipient.
     */
    protected function sendToRecipient(array $recipient): void
    {
        // Check if email already sent
        $existingLog = EmailLog::where('status', 'sent')
            ->where('email_type', $this->emailType)
            ->where('recipient_email', $recipient['email'])
            ->where('model_type', $recipient['model_type'] ?? null)
            ->where('model_id', $recipient['model_id'] ?? null)
            ->first();

        if ($existingLog) {
            \Log::info('SendBulkEmailJob: Email skipped - already sent', [
                'recipient' => $recipient['email'],
                'email_type' => $this->emailType,
                'model_type' => $recipient['model_type'] ?? null,
                'model_id' => $recipient['model_id'] ?? null,
                'existing_log_id' => $existingLog->id,
                'existing_sent_at' => $existingLog->sent_at,
            ]);
            return;
        }

        \Log::info('SendBulkEmailJob: No existing sent log found, proceeding to send', [
            'recipient' => $recipient['email'],
            'email_type' => $this->emailType,
            'model_type' => $recipient['model_type'] ?? null,
            'model_id' => $recipient['model_id'] ?? null,
        ]);

        try {
            // Send the email
            Mail::to($recipient['email'])->send($this->mailable);

            // Determine user_id: use model_id if model_type is User, else recipient's user_id
            $userId = $recipient['user_id'] ?? null;
            if (($recipient['model_type'] ?? null) === 'App\Models\User' && ($recipient['model_id'] ?? null)) {
                $userId = $recipient['model_id'];
            }

            // Log successful send
            try {
                EmailLog::create([
                    'email_type' => $this->emailType,
                    'recipient_email' => $recipient['email'],
                    'user_id' => $userId,
                    'model_type' => $recipient['model_type'] ?? null,
                    'model_id' => $recipient['model_id'] ?? null,
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
            } catch (\Exception $e) {
                \Log::error('SendBulkEmailJob: Failed to create EmailLog for recipient', [
                    'email' => $recipient['email'],
                    'error' => $e->getMessage(),
                ]);
            }

        } catch (Throwable $e) {
            // Determine user_id: use model_id if model_type is User, else recipient's user_id
            $userId = $recipient['user_id'] ?? null;
            if (($recipient['model_type'] ?? null) === 'App\Models\User' && ($recipient['model_id'] ?? null)) {
                $userId = $recipient['model_id'];
            }

            // Log the failure but don't fail the entire job
            try {
                EmailLog::create([
                    'email_type' => $this->emailType,
                    'recipient_email' => $recipient['email'],
                    'user_id' => $userId,
                    'model_type' => $recipient['model_type'] ?? null,
                    'model_id' => $recipient['model_id'] ?? null,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            } catch (\Exception $logException) {
                \Log::error('SendBulkEmailJob: Failed to log failure for recipient', [
                    'email' => $recipient['email'],
                    'original_error' => $e->getMessage(),
                    'log_error' => $logException->getMessage(),
                ]);
            }

            // Log error but continue processing other recipients
            \Log::warning('Failed to send bulk email to recipient', [
                'email' => $recipient['email'],
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        \Log::error('SendBulkEmailJob failed permanently', [
            'email_type' => $this->emailType,
            'recipient_count' => count($this->recipients),
            'error' => $exception->getMessage(),
        ]);
    }
}