<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Size;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Size::factory()->count(6)->create()->each(function ($size) {
            $size->products()->attach(
                Product::inRandomOrder()->pluck('id')
            );
        });
    }
}
