<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Amenity::factory()
            ->count(6)
            ->sequence(fn($sequence) => [
                'sort_order' => $sequence->index + 1,
            ])
            ->create();
    }
}
