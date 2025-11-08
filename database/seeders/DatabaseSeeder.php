<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Roles::create([
            'name' => 'admin',
        ]);


        Roles::create([
            'name' => 'marketing',
        ]);


        Roles::create([
            'name' => 'customer',
        ]);

        User::factory()->create([
            'name' => "admin",
            'email' => 'admin@gmail.com',
            'role' => '1',
            'password' => Hash::make('password')
        ]);

        for ($i = 0; $i < 10; $i++) {
            $num = $i + 1;
            User::factory()->create([
                'name' => "Agent $num",
                'email' => "ageng$num@gmail.com",
                'role' => 2,
                'password' => Hash::make('password'),
            ]);
        }
    }
}
