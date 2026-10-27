<?php

namespace Database\Factories;

use App\Models\PropertyType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PropertyType>
 */
class PropertyTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Luxury Villa',
            'Executive Residence',
            'Premium Apartment',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'tagline' => fake()->sentence(6),
            'description' => fake()->paragraph(),
            'size_sqm' => fake()->numberBetween(80, 600),
            'bedrooms' => fake()->numberBetween(1, 6),
            'units_count' => fake()->numberBetween(1, 20),
            'is_featured' => fake()->boolean(25),
        ];
    }
}
