<?php

namespace Database\Seeders;

use App\Models\PropertyType;
use Illuminate\Database\Seeder;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PropertyType::factory()
            ->count(3)
            ->sequence(fn($sequence) => [
                'sort_order' => $sequence->index + 1,
            ])->create();
    }
}
