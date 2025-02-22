<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $this->call([
            // ProductSeeder::class,
            // CollectionSeeder::class,
            SizeSeeder::class,
            // OrderSeeder::class,
            // ReviewSeeder::class,
        ]);
    }
}
