<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;
    protected static ?int $role;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'role' => static::$role ??= 3,
            'bio' => $this->faker->paragraph(8),
            'profile_img' => $this->faker->randomElement([
                'assets/images/person_1-min.jpg',
                'assets/images/person_2-min.jpg',
                'assets/images/person_3-min.jpg',
                'assets/images/person_4-min.jpg',
                'assets/images/person_5-min.jpg',
                'assets/images/person_6-min.jpg',
            ]),
            'password' => static::$password ??= Hash::make('password'),
            'wa_url' => $this->faker->url(),
            'tele_url' => $this->faker->url(),
            'ig_url' => $this->faker->url(),
            'x_url' => $this->faker->url(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
