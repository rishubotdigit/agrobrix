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
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@agrobrix.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'mobile' => '1234567890',
                'verified_at' => now(),
            ],
            [
                'name' => 'Agent User',
                'email' => 'agent@agrobrix.com',
                'password' => Hash::make('password'),
                'role' => 'agent',
                'mobile' => '1234567891',
                'verified_at' => now(),
            ],
            [
                'name' => 'Owner User',
                'email' => 'owner@agrobrix.com',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'mobile' => '1234567892',
                'verified_at' => now(),
            ],
            [
                'name' => 'Buyer User',
                'email' => 'buyer@agrobrix.com',
                'password' => Hash::make('password'),
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

            // Assign Pro plan to owner user for testing
            if ($userData['role'] === 'owner') {
                $proPlan = Plan::where('name', 'Pro')->first();
                if ($proPlan) {
                    $user->update(['plan_id' => $proPlan->id]);
                }
            }
        }
    }
}