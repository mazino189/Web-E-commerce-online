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
        
        // Vietnamese user seeding //
        $users = [
            ['name' => 'Lê Thị C', 'email' => 'lethic@gmail.com'],
            ['name' => 'Phạm Văn D', 'email' => 'phamvand@gmail.com'],
            ['name' => 'Hoàng Tuấn E', 'email' => 'hoangtuane@gmail.com'],
            ['name' => 'Ngô Ngọc F', 'email' => 'ngongocf@gmail.com'],
            ['name' => 'Vũ Đức G', 'email' => 'vuducg@gmail.com'],
            ['name' => 'Đặng Kim H', 'email' => 'dangkimh@gmail.com'],
            ['name' => 'Nguyễn Văn A', 'email' => 'nguyenvana@gmail.com'],
        ];

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
