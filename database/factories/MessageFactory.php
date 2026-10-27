<?php

namespace Database\Factories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'phone' => '+232 ' . fake()->numerify('## ### ###'),
            'email' => fake()->optional(0.6)->safeEmail(),
            'body' => fake()->paragraph(),
            'read_at' => null,
            'created_at' => fake()->dateTimeBetween('-3 weeks'),
        ];
    }

    public function read(): static
    {
        return $this->state(fn() => ['read_at' => now()]);
    }
}
