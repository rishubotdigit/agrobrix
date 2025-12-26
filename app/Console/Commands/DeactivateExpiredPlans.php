<?php

namespace App\Console\Commands;

use App\Mail\PlanDeactivated;
use App\Mail\PlanExpirationWarning;
use App\Models\Notification;
use App\Models\PlanPurchase;
use App\Traits\EmailQueueTrait;
use Illuminate\Console\Command;

class DeactivateExpiredPlans extends Command
{
    use EmailQueueTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plans:deactivate-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate plan purchases that have expired and send notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Send expiration warnings for plans expiring in 7 days
        $warningDate = now()->addDays(7);
        $expiringSoonPurchases = PlanPurchase::where('status', 'activated')
            ->where('expires_at', '>=', now())
            ->where('expires_at', '<=', $warningDate)
            ->get();

        foreach ($expiringSoonPurchases as $purchase) {
            // Send email
            $this->sendOrQueueEmail(
                new PlanExpirationWarning($purchase),
                $purchase->user->email,
                $purchase->user_id,
                'App\Models\User',
                $purchase->user_id,
                'plan_expiration_warning'
            );

            // Create in-app notification
            Notification::create([
                'user_id' => $purchase->user_id,
                'type' => 'plan_expiration_warning',
                'message' => 'Your plan "' . $purchase->plan->name . '" is expiring soon on ' . $purchase->expires_at->format('Y-m-d') . '.',
                'data' => [
                    'plan_purchase_id' => $purchase->id,
                    'plan_id' => $purchase->plan_id,
                    'expires_at' => $purchase->expires_at,
                ],
            ]);

            $this->info("Sent expiration warning for plan purchase ID: {$purchase->id} to user ID: {$purchase->user_id}");
        }

        // Deactivate expired plans
        $expiredPurchases = PlanPurchase::where('status', 'activated')
            ->where('expires_at', '<', now())
            ->get();

        foreach ($expiredPurchases as $purchase) {
            $purchase->update(['status' => 'expired']);

            // Send email
            $this->sendOrQueueEmail(
                new PlanDeactivated($purchase),
                $purchase->user->email,
                $purchase->user_id,
                'App\Models\User',
                $purchase->user_id,
                'plan_deactivated'
            );

            // Create in-app notification
            Notification::create([
                'user_id' => $purchase->user_id,
                'type' => 'plan_deactivated',
                'message' => 'Your plan "' . $purchase->plan->name . '" has been deactivated as it expired on ' . $purchase->expires_at->format('Y-m-d') . '.',
                'data' => [
                    'plan_purchase_id' => $purchase->id,
                    'plan_id' => $purchase->plan_id,
                    'expires_at' => $purchase->expires_at,
                ],
            ]);

            $this->info("Deactivated plan purchase ID: {$purchase->id} for user ID: {$purchase->user_id}");
        }

        $this->info("Sent warnings for {$expiringSoonPurchases->count()} expiring plans and deactivated {$expiredPurchases->count()} expired plan purchases.");
    }
}