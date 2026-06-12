<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       // admin user seeding //
       User::firstOrCreate(
           ['email' => 'admin@gmail.com'],
           [
               'name' => 'Admin User',
               'password' => bcrypt('password'),
               'role' => 'admin',
           ]
       );
        
        // user seeding //
        User::firstOrCreate(
            ['email' => 'John@gmail.com'],
            [
                'name' => 'John Doe',
                'password' => bcrypt('password'),
                'role' => 'user',
            ]
        );

        // call other seeders //
        $this->call([
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
        ]);
    }
}

