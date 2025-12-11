<?php

namespace App\Console\Commands;

use App\Models\PlanPurchase;
use Illuminate\Console\Command;

class DeactivateExpiredPlans extends Command
{
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
    protected $description = 'Deactivate plan purchases that have expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredPurchases = PlanPurchase::where('status', 'activated')
            ->where('expires_at', '<', now())
            ->get();

        foreach ($expiredPurchases as $purchase) {
            $purchase->update(['status' => 'expired']);
            $this->info("Deactivated plan purchase ID: {$purchase->id} for user ID: {$purchase->user_id}");
        }

        $this->info("Deactivated {$expiredPurchases->count()} expired plan purchases.");
    }
}