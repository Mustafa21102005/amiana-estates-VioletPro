<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Feature;
use App\Models\PropertyType;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $propertyTypes = PropertyType::all();
        $categories = Category::all();

        $features = [
            'Premium Finishes',
            'Modern Design',
            'Spacious Layout',
        ];

        foreach ($propertyTypes as $propertyType) {
            foreach ($categories as $category) {
                foreach ($features as $index => $title) {
                    Feature::updateOrCreate(
                        [
                            'property_type_id' => $propertyType->id,
                            'category_id' => $category->id,
                            'title' => $title,
                        ],
                        [
                            'sort_order' => $index + 1,
                        ]
                    );
                }
            }
        }
    }
}
