<?php

namespace Database\Seeders;

use App\Models\Addon;
use Illuminate\Database\Seeder;

class AddonSeeder extends Seeder
{
    public function run(): void
    {
        $addons = [
            // Contact add-ons for buyers and agents
            [
                'name' => 'Extra 25 Contacts',
                'price' => 249.00,
                'description' => 'Add 25 additional contact views to your plan',
                'capabilities' => [
                    'max_contacts' => 25,
                ],
            ],
            [
                'name' => 'Extra 50 Contacts',
                'price' => 449.00,
                'description' => 'Add 50 additional contact views to your plan',
                'capabilities' => [
                    'max_contacts' => 50,
                ],
            ],
            [
                'name' => 'Extra 100 Contacts',
                'price' => 799.00,
                'description' => 'Add 100 additional contact views to your plan',
                'capabilities' => [
                    'max_contacts' => 100,
                ],
            ],

            // Listing add-ons for owners and agents
            [
                'name' => 'Extra 5 Property Listings',
                'price' => 199.00,
                'description' => 'Add 5 additional property listings to your plan',
                'capabilities' => [
                    'max_listings' => 5,
                ],
            ],

            // Featured listing add-ons
            [
                'name' => 'Extra 5 Featured Listings',
                'price' => 299.00,
                'description' => 'Add 5 additional featured listings to your plan',
                'capabilities' => [
                    'max_featured_listings' => 5,
                ],
            ],

            // Premium features add-on
            [
                'name' => 'Priority Support',
                'price' => 99.00,
                'description' => 'Get priority customer support and faster response times',
                'capabilities' => [
                    'priority_support' => true,
                ],
            ],
        ];

        foreach ($addons as $addonData) {
            Addon::create($addonData);
        }
    }
}