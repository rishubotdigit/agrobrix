<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Amenity;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Water Source' => [
                'Borewell (Active)',
                'Open Well',
                'River Access/Frontage',
                'Canal Access',
                'Natural Stream/Nala',
                'Water Tank/Reservoir',
                'Rainwater Harvesting Pit',
            ],
            'Irrigation' => [
                'Drip Irrigation System',
                'Sprinkler System',
                'Pipeline Installed',
            ],
            'Electricity & Power' => [
                'Single Phase Electricity',
                '3-Phase Electricity (Agricultural)',
                'Solar Power Setup',
                'Transformer Nearby',
            ],
            'Structures' => [
                'Farmhouse (RCC)',
                'Farmhouse (Tiled/Traditional)',
                'Labour/Servant Quarters',
                'Cattle Shed/Goshala',
                'Warehouse/Godown',
                'Tractor/Tool Shed',
                'Polyhouse/Greenhouse',
                'Poultry Shed',
            ],
            'Security & Fencing' => [
                'Barbed Wire Fencing',
                'Chain Link Fencing',
                'Compound Wall',
                'Solar Fencing',
                'Gated Entry',
            ],
            'Plantation & Soil' => [
                'Coconut Plantation',
                'Arecanut Plantation',
                'Fruit Orchard (Mango/Guava etc.)',
                'Timber/Teak Plantation',
                'Certified Organic Soil',
                'Red Soil',
                'Black Cotton Soil',
            ],
            'Access & Terrain' => [
                'Tar Road Access',
                'Mud Road Access',
                'Highway Proximity',
                'Village Proximity',
            ],
            'Scenic & Leisure' => [
                'Waterfall',
                'Lake View',
                'Hilltop/Valley View',
                'Forest Adjoining',
            ],
        ];

        foreach ($categories as $categoryName => $subcategories) {
            $category = Category::create(['name' => $categoryName]);

            foreach ($subcategories as $subcategoryName) {
                $subcategory = Subcategory::create([
                    'name' => $subcategoryName,
                    'category_id' => $category->id,
                ]);

                // For this structure, each subcategory has one amenity with the same name
                Amenity::create([
                    'name' => $subcategoryName,
                    'subcategory_id' => $subcategory->id,
                ]);
            }
        }
    }
}