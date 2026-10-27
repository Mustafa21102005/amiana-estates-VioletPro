<?php

namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Setting>
 */
class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $name = fake()->unique()->randomElement([
                'tagline',
                'phone',
                'whatsapp',
                'email',
                'address',
                'map_url',
                'facebook',
                'instagram',
            ]),
            'value' => match ($name) {
                'tagline' => 'Your trusted partner for quality services.',
                'phone' => '+966 11 234 5678',
                'whatsapp' => 'https://wa.me/966501234567',
                'email' => 'info@example.com',
                'address' => 'Aberdeen, Sierra Leone',
                'map_url' => 'https://maps.google.com/?q=Aberdeen+Sierra+Leone',
                'facebook' => 'https://www.facebook.com/example',
                'instagram' => 'https://www.instagram.com/example',
            },
        ];
    }
}
