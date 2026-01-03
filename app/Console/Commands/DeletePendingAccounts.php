<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeletePendingAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:delete-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently delete user accounts that have been pending deletion for more than 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoffTime = now()->subHours(24);
        
        $pendingUsers = User::whereNotNull('deletion_requested_at')
            ->where('deletion_requested_at', '<', $cutoffTime)
            ->get();

        $deletedCount = 0;

        foreach ($pendingUsers as $user) {
            $userId = $user->id;
            $userEmail = $user->email;
            $userName = $user->name;
            $userRole = $user->role;
            
            try {
                // Delete the user (cascade will handle related records if set up)
                $user->delete();
                $deletedCount++;
                
                Log::info("Permanently deleted user account", [
                    'user_id' => $userId,
                    'email' => $userEmail,
                    'name' => $userName,
                    'role' => $userRole,
                    'deletion_requested_at' => $user->deletion_requested_at,
                ]);
                
                $this->info("Deleted user ID: {$userId} ({$userEmail})");
            } catch (\Exception $e) {
                Log::error("Failed to delete user account", [
                    'user_id' => $userId,
                    'email' => $userEmail,
                    'error' => $e->getMessage(),
                ]);
                
                $this->error("Failed to delete user ID: {$userId} - {$e->getMessage()}");
            }
        }

        $this->info("Permanently deleted {$deletedCount} user account(s) that were pending deletion for more than 24 hours.");
        
        return Command::SUCCESS;
    }
}
