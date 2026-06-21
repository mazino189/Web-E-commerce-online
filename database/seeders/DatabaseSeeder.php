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
           ['email' => 'manager@example.com'],
           [
               'name' => 'Manager',
               'password' => bcrypt('123456'),
               'role' => 'admin',
           ]
       );

       User::firstOrCreate(
           ['email' => 'admin@gmail.com'],
           [
               'name' => 'Admin',
               'password' => bcrypt('password'),
               'role' => 'admin',
           ]
       );

        foreach ($users as $u) {
            User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => bcrypt('123456'),
                    'role' => 'user',
                ]
            );
        }

        // call other seeders //
        $this->call([
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
