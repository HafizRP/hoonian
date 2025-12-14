<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $property = Property::inRandomOrder()->first();
        
        return [
            'user_id' => User::where('role', '!=', 1)->inRandomOrder()->first()->id ?? User::factory(),
            'property_id' => $property->id,
            'amount' => $property->price,
            'status' => $this->faker->randomElement(['leading', 'outbid', 'accepted']),
        ];
    }
}
