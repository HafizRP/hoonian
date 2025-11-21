<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\PropertyType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->streetName(),
            'price' => $this->faker->randomFloat(2, 50000000, 5000000000), // 50 jt - 5 M
            'address' => $this->faker->address(),
            'thumbnail' => $this->faker->randomElement([
                'assets/images/hero_bg_1.jpg',
                'assets/images/hero_bg_2.jpg',
                'assets/images/hero_bg_3.jpg',
                'assets/images/img_1.jpg',
                'assets/images/img_2.jpg',
                'assets/images/img_3.jpg',
                'assets/images/img_4.jpg',
                'assets/images/img_5.jpg',
                'assets/images/img_6.jpg',
                'assets/images/img_7.jpg',
                'assets/images/img_8.jpg',
            ]),
            'description' => $this->faker->paragraph(8),
            'city' => $this->faker->city(),
            'land_area' => $this->faker->randomFloat(2, 50, 500), // m2
            'building_area' => $this->faker->randomFloat(2, 30, 400), // m2
            'bedrooms' => $this->faker->numberBetween(1, 6),
            'bathrooms' => $this->faker->numberBetween(1, 4),
            'floors' => $this->faker->numberBetween(1, 3),
            'maps_url' => 'https://www.google.com/maps/place/' . urlencode($this->faker->address()),
            'status' => $this->faker->randomElement(['0', '1']), // 0 = tidak aktif, 1 = aktif,
            'featured' => $this->faker->randomElement([true, false]),
            'popular' => $this->faker->randomElement([true, false]),
            'owner_id' => User::factory(), // relasi ke users
            'property_type' => $this->faker->randomElement([1, 2, 3, 4, 5]), // relasi ke properties_type
        ];
    }
}
