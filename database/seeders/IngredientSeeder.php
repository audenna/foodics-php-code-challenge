<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Repositories\IngredientRepository;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        # instantiate the Ingredient repository
        (new IngredientRepository(new Ingredient()))->seedDefaultIngredients();
    }
}
