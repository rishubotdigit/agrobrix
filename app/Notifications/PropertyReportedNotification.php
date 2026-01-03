<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PropertyReportedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $report)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database']; // Keeping it simple with database notifications first
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'property_report',
            'title' => 'New Property Report',
            'message' => 'Property "' . $this->report->property->title . '" has been reported for: ' . $this->report->reason,
            'report_id' => $this->report->id,
            'property_id' => $this->report->property_id,
            'user_id' => $this->report->user_id,
        ];
    }
}
