<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        # seed the Burger product
        (new ProductRepository(new Product()))->createModel(['name' => 'Burger']);
    }
}
