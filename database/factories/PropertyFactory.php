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
        $faker = \Faker\Factory::create('id_ID');

        return [
            'name' => 'Rumah ' . $faker->streetName(),
            'price' => $faker->numberBetween(500, 5000) * 1000000, // 500 jt - 5 M (kelipatan juta)
            'address' => $faker->address(),
            'thumbnail' => function () {
                $images = [
                    'hero_bg_1.jpg', 'hero_bg_2.jpg', 'hero_bg_3.jpg',
                    'img_1.jpg', 'img_2.jpg', 'img_3.jpg', 'img_4.jpg',
                    'img_5.jpg', 'img_6.jpg', 'img_7.jpg', 'img_8.jpg'
                ];
                $imageName = \Illuminate\Support\Arr::random($images);
                $sourcePath = public_path("assets/images/{$imageName}");
                $destPath = "properties/seed/{$imageName}";

                // Ensure directory exists and copy file if source exists
                if (file_exists($sourcePath)) {
                    // Create directory if not exists
                    if (!\Illuminate\Support\Facades\Storage::disk('public')->exists('properties/seed')) {
                        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('properties/seed');
                    }
                    
                    // Copy file to storage/app/public/properties/seed/
                    // Note: copy() needs absolute paths or streams. 
                    // Let's use Storage::put with file_get_contents
                    if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($destPath)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->put($destPath, file_get_contents($sourcePath));
                    }
                    
                    return $destPath;
                }
                
                // Fallback if file not found (e.g. initial run before assets exist)
                return "properties/seed/{$imageName}"; 
            },
            'description' => $faker->paragraph(5),
            'city' => $faker->city(), // Kota di Indonesia
            'land_area' => $faker->numberBetween(60, 500), // m2
            'building_area' => $faker->numberBetween(36, 400), // m2
            'bedrooms' => $faker->numberBetween(2, 6),
            'bathrooms' => $faker->numberBetween(1, 4),
            'floors' => $faker->numberBetween(1, 3),
            'maps_url' => 'https://www.google.com/maps/search/?api=1&query=' . urlencode($faker->address()),
            'status' => '1',
            'featured' => $faker->boolean(20), // 20% chance
            'popular' => $faker->boolean(30), // 30% chance
            'owner_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'property_type' => $faker->numberBetween(1, 5),
        ];
    }
}
