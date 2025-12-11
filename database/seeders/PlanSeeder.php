<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic',
                'price' => 499.00,
                'description' => 'Perfect for getting started with property listings',
                'capabilities' => [
                    'max_listings' => 5,
                    'max_contacts' => 10,
                ],
            ],
            [
                'name' => 'Pro',
                'price' => 999.00,
                'description' => 'Advanced features for serious property professionals',
                'capabilities' => [
                    'max_listings' => 20,
                    'max_contacts' => 50,
                ],
            ],
            [
                'name' => 'Enterprise',
                'price' => 0.00, // Custom pricing
                'description' => 'Unlimited access for large organizations',
                'capabilities' => [
                    'max_listings' => 1000, // Effectively unlimited
                    'max_contacts' => 1000, // Effectively unlimited
                ],
            ],
        ];

        foreach ($plans as $planData) {
            Plan::create($planData);
        }
    }
}