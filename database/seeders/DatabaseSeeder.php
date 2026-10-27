<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
        ]);

        $this->call([
            AmenitySeeder::class,
            CategorySeeder::class,
            PropertyTypeSeeder::class,
            ContentSectionSeeder::class,
            FeatureSeeder::class,
            SettingSeeder::class,
            MediaSeeder::class,
            MessageSeeder::class
        ]);
    }
}
