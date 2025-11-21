<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    protected static ?int $property_id;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => User::factory(),
            'property_id' => $this->faker->numberBetween(1, 20),
            'rating' => $this->faker->numberBetween(1, 5),
            'description' => $this->faker->paragraph(3)
        ];
    }
}
