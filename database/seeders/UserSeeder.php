<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@agrobrix.com'],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@agrobrix.com',
                'password' => Hash::make('SuperAdmin@2026!'),
                'role' => 'admin',
                'mobile' => '9876543210',
                'verified_at' => now(),
                'email_verified_at' => now(),
            ]
        );

        // Create regular test users (only in development)
        if (app()->environment(['local', 'development'])) {
            $users = [
                [
                    'name' => 'Admin User',
                    'email' => 'admin@agrobrix.com',
                    'password' => Hash::make('Admin@123'),
                    'role' => 'admin',
                    'mobile' => '1234567890',
                    'verified_at' => now(),
                ],
                [
                    'name' => 'Agent User',
                    'email' => 'agent@agrobrix.com',
                    'password' => Hash::make('Agent@123'),
                    'role' => 'agent',
                    'mobile' => '1234567891',
                    'verified_at' => now(),
                ],
                [
                    'name' => 'Owner User',
                    'email' => 'owner@agrobrix.com',
                    'password' => Hash::make('Owner@123'),
                    'role' => 'owner',
                    'mobile' => '1234567892',
                    'verified_at' => now(),
                ],
                [
                    'name' => 'Buyer User',
                    'email' => 'buyer@agrobrix.com',
                    'password' => Hash::make('Buyer@123'),
                    'role' => 'buyer',
                    'mobile' => '1234567893',
                    'verified_at' => now(),
                ],
            ];

            foreach ($users as $userData) {
                $user = User::updateOrCreate(
                    ['email' => $userData['email']],
                    $userData
                );

                // Assign Premium plan to owner user for testing
                if ($userData['role'] === 'owner') {
                    $premiumPlan = Plan::where('name', 'Premium')
                        ->where('role', 'owner')
                        ->first();
                    if ($premiumPlan) {
                        $user->update(['plan_id' => $premiumPlan->id]);
                    }
                }

                // Assign Professional plan to agent user for testing
                if ($userData['role'] === 'agent') {
                    $professionalPlan = Plan::where('name', 'Professional')
                        ->where('role', 'agent')
                        ->first();
                    if ($professionalPlan) {
                        $user->update(['plan_id' => $professionalPlan->id]);
                    }
                }

                // Assign Explorer plan to buyer user for testing
                if ($userData['role'] === 'buyer') {
                    $explorerPlan = Plan::where('name', 'Explorer')
                        ->where('role', 'buyer')
                        ->first();
                    if ($explorerPlan) {
                        $user->update(['plan_id' => $explorerPlan->id]);
                    }
                }
            }
        }
    }
}