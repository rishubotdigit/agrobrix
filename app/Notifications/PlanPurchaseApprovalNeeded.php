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
        $payment = $this->planPurchase->payment;
        $user = $this->planPurchase->user;
        $plan = $this->planPurchase->plan;

        return (new MailMessage)
            ->subject('New Plan Purchase Requires Approval - ' . $plan->name)
            ->greeting('New Plan Purchase Requires Approval')
            ->line('A user has submitted a plan purchase payment that requires your approval.')
            ->line('')
            ->line('**Invoice Details:**')
            ->line('**Customer:** ' . $user->name)
            ->line('**Email:** ' . $user->email)
            ->line('**Plan:** ' . $plan->name)
            ->line('**Amount:** ₹' . number_format($payment->amount))
            ->line('**Payment Method:** UPI Static QR')
            ->line('**Transaction ID:** ' . $payment->transaction_id)
            ->line('**Submission Date:** ' . $payment->updated_at->format('M d, Y \a\t h:i A'))
            ->line('')
            ->line('**Plan Features:**')
            ->when($plan->max_contacts > 0, function ($mail) use ($plan) {
                return $mail->line('• Contact Views: ' . $plan->max_contacts);
            })
            ->when($plan->max_featured_listings > 0, function ($mail) use ($plan) {
                return $mail->line('• Featured Listings: ' . $plan->max_featured_listings);
            })
            ->when($plan->validity_period_days > 0, function ($mail) use ($plan) {
                return $mail->line('• Validity Period: ' . $plan->validity_period_days . ' days');
            })
            ->line('')
            ->action('Review & Approve Purchase', url('/admin/plan-purchases/' . $this->planPurchase->id))
            ->line('Please verify the payment details and approve or reject the purchase accordingly.');
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
