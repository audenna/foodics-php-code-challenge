<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use App\Models\ProductIngredient;
use App\Repositories\IngredientRepository;
use App\Repositories\ProductIngredientRepository;
use App\Repositories\ProductRepository;
use Illuminate\Database\Seeder;

class ProductIngredientSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        # seed the default data
        (new ProductIngredientRepository(new ProductIngredient()))->seedDefaultProductIngredients(
            new ProductRepository(new Product()),
            new IngredientRepository(new Ingredient())
        );
    }
}
