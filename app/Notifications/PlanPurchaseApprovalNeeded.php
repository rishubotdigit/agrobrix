<?php

namespace App\Notifications;

use App\Models\PlanPurchase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PlanPurchaseApprovalNeeded extends Notification implements ShouldQueue
{
    use Queueable;

    public PlanPurchase $planPurchase;

    /**
     * Create a new notification instance.
     */
    public function __construct(PlanPurchase $planPurchase)
    {
        $this->planPurchase = $planPurchase;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Plan Purchase Requires Approval')
            ->line('A user has purchased a plan and requires approval.')
            ->line('User: ' . $this->planPurchase->user->name)
            ->line('Plan: ' . $this->planPurchase->plan->name)
            ->action('Review Purchase', url('/admin/plan-purchases/' . $this->planPurchase->id))
            ->line('Please review and approve the purchase.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'New plan purchase requires approval',
            'plan_purchase_id' => $this->planPurchase->id,
            'user_name' => $this->planPurchase->user->name,
            'plan_name' => $this->planPurchase->plan->name,
        ];
    }
}
