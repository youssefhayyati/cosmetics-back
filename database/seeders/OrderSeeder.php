<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::factory()->count(10)->create()->each(function ($order) {
            $order->products()->attach(
                Product::inRandomOrder()->limit(3)->pluck('id')
            );
        });
    }
}
