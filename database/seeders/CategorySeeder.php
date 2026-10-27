<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::factory()
            ->count(3)
            ->sequence(fn($sequence) => [
                'sort_order' => $sequence->index + 1,
            ])
            ->create();
    }
}
