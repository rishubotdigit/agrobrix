<?php

namespace App\Traits;

use App\Jobs\SendEmailJob;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

trait EmailQueueTrait
{
    /**
     * Send or queue an email based on the queue_mode setting.
     *
     * @param mixed $mailable The mailable instance
     * @param string $recipientEmail The recipient's email address
     * @param int|null $userId The user ID for logging
     * @param string|null $modelType The model type for logging
     * @param int|null $modelId The model ID for logging
     * @param string $emailType The email type for logging
     */
    protected function sendOrQueueEmail($mailable, string $recipientEmail, ?int $userId = null, ?string $modelType = null, ?int $modelId = null, string $emailType = 'general'): void
    {
        $queueMode = Setting::get('queue_mode', 'disabled');

        if ($queueMode === 'disabled') {
            return;
        }

        // Check if email already sent with database locking
        $existingLog = DB::transaction(function () use ($emailType, $recipientEmail, $modelType, $modelId) {
            return \App\Models\EmailLog::where('status', 'sent')
                ->where('email_type', $emailType)
                ->where('recipient_email', $recipientEmail)
                ->where('model_type', $modelType)
                ->where('model_id', $modelId)
                ->lockForUpdate()
                ->exists();
        });

        if ($existingLog) {
            \Log::info('EmailQueueTrait: Email already sent, skipping dispatch', [
                'recipient' => $recipientEmail,
                'email_type' => $emailType,
                'model_type' => $modelType,
                'model_id' => $modelId,
                'user_id' => $userId,
            ]);
            return;
        }

        \Log::info('EmailQueueTrait: Dispatching SendEmailJob', [
            'recipient' => $recipientEmail,
            'email_type' => $emailType,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'user_id' => $userId,
        ]);

        // Dispatch job to send email asynchronously
        SendEmailJob::dispatch(
            $mailable,
            $recipientEmail,
            $userId,
            $modelType,
            $modelId,
            $emailType
        );
    }
}