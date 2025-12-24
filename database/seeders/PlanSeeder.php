<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            // Buyer Plans
            [
                'name' => 'Starter',
                'role' => 'buyer',
                'price' => 249.00,
                'original_price' => 499.00,
                'discount' => 50,
                'contacts_to_unlock' => 5,
                'validity_days' => 30,
                'persona' => 'Testing the waters for a specific plot.',
                'status' => 'active'
            ],
            [
                'name' => 'Explorer',
                'role' => 'buyer',
                'price' => 399.00,
                'original_price' => 999.00,
                'discount' => 60,
                'contacts_to_unlock' => 15,
                'validity_days' => 90,
                'persona' => 'Serious buyers comparing multiple locations.',
                'status' => 'active'
            ],
            [
                'name' => 'Investor',
                'role' => 'buyer',
                'price' => 749.00,
                'original_price' => 2499.00,
                'discount' => 70,
                'contacts_to_unlock' => 40,
                'validity_days' => 180,
                'persona' => 'Professional farmers or long-term investors.',
                'status' => 'active'
            ],

            // Owner Plans
            [
                'name' => 'Basic',
                'role' => 'owner',
                'price' => 0.00,
                'features' => ['Standard Listing', '1 Photo', 'Engagement dashboard'],
                'capabilities' => ['max_listings' => 1],
                'validity_days' => 30,
                'status' => 'active'
            ],
            [
                'name' => 'Premium',
                'role' => 'owner',
                'price' => 0.00,
                'original_price' => 99.00,
                'discount' => 100,
                'features' => ['Top of Search', '5 Photos', '"Who Viewed My Contact" Visibility'],
                'capabilities' => ['max_listings' => 3, 'max_featured_listings' => 1],
                'validity_days' => 30,
                'status' => 'active'
            ],
            [
                'name' => 'Elite',
                'role' => 'owner',
                'price' => 249.00,
                'original_price' => 499.00,
                'discount' => 50,
                'features' => ['Highlighted Tag', 'Social Media Shoutout', 'Priority Support', 'Post 3 Properties'],
                'capabilities' => ['max_listings' => 3, 'max_featured_listings' => 3],
                'validity_days' => 90,
                'status' => 'active'
            ],

            // Agent Plans
            [
                'name' => 'Basic',
                'role' => 'agent',
                'price' => 0.00,
                'features' => ['Standard Listing', '1 Photo', 'Engagement dashboard'],
                'capabilities' => ['max_contacts' => 5, 'max_listings' => 1],
                'validity_days' => 30,
                'status' => 'active'
            ],
            [
                'name' => 'Professional',
                'role' => 'agent',
                'price' => 999.00,
                'original_price' => 1999.00,
                'discount' => 50,
                'features' => ['15 Property Posts', 'Analytics Dashboard'],
                'capabilities' => ['max_contacts' => 50, 'max_listings' => 15],
                'validity_days' => 90,
                'status' => 'active'
            ],
            [
                'name' => 'Business',
                'role' => 'agent',
                'price' => 1999.00,
                'original_price' => 4999.00,
                'discount' => 60,
                'features' => ['50 Property Posts', 'Agent Profile Page'],
                'capabilities' => ['max_contacts' => 150, 'max_listings' => 50],
                'validity_days' => 180,
                'status' => 'active'
            ],
            [
                'name' => 'Enterprise',
                'role' => 'agent',
                'price' => 3749.00,
                'original_price' => 14999.00,
                'discount' => 75,
                'features' => ['150 Property Posts', 'Verified Partner Badge'],
                'capabilities' => ['max_contacts' => 500, 'max_listings' => 150],
                'validity_days' => 360,
                'status' => 'active'
            ],
        ];

        foreach ($plans as $planData) {
            Plan::create($planData);
        }
    }
}