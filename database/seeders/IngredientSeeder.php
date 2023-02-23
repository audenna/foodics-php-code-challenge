<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Repositories\IngredientRepository;
use App\Utils\Utils;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        # here, we are saving the ingredient volumes in grams
        $ingredients = [
            [
                'name' => 'Beef',
                'available_stock_in_gram' => Utils::convertKgToGrams(20),
            ],
            [
                'name' => 'Cheese',
                'available_stock_in_gram' => Utils::convertKgToGrams(5),
            ],
            [
                'name' => 'Onion',
                'available_stock_in_gram' => Utils::convertKgToGrams(1),
            ],
        ];

        # instantiate the Ingredient repository
        $repository = new IngredientRepository(new Ingredient());
        foreach ($ingredients as $ingredient) {
            $repository->createModel($ingredient);
        }
    }
}
