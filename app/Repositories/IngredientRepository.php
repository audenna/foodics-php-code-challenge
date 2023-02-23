<?php

namespace App\Repositories;

use App\Models\Ingredient;
use App\Repositories\Base\BaseRepositoryAbstract;
use App\Utils\Utils;
use Illuminate\Support\Facades\Log;

class IngredientRepository extends BaseRepositoryAbstract
{

    /**
     * @var string
     */
    protected string $databaseTableName = 'ingredients';

    /**
     *
     * @param Ingredient $ingredient
     */
    public function __construct(Ingredient $ingredient)
    {
        parent::__construct($ingredient, $this->databaseTableName);
    }

    /**
     *
     * @return void
     */
    public function seedDefaultIngredients(): void
    {
        try {

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

            foreach ($ingredients as $ingredient) {
                if (! $this->findSingleByWhereClause(['name' => $ingredient['name']])) {
                    $this->createModel($ingredient);
                }
            }

        } catch (\Exception $exception) { Log::error($exception); }
    }
}
