<?php

namespace Database\Factories;

use App\Models\Amenity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Amenity>
 */
class AmenityFactory extends Factory
{
    /**
     * title => icon. Every sample keeps its matching icon, so a random pick can never mismatch them.
     * The icon names come from App\Support\AmenityIcons.
     */
    private const SAMPLES = [
        'Swimming Pool' => 'waves',
        'Gym' => 'dumbbell',
        'Free Wi-Fi' => 'wifi',
        'Parking' => 'car',
        'Restaurant' => 'utensils',
        'Spa' => 'spa',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->randomElement(array_keys(self::SAMPLES));

        return [
            'title' => $title,
            'description' => fake()->sentence(),
            'icon' => self::SAMPLES[$title],
        ];
    }
}
