<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Collection::factory(3)->create()
            ->each(function ($collection) {
                $collection->products()->attach(
                    Product::inRandomOrder()->limit(6)->pluck('id')
                );
            });
    }
}
