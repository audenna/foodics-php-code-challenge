<?php

namespace App\Repositories;

use App\Models\Ingredient;
use App\Repositories\Base\BaseRepositoryAbstract;
use App\Utils\Utils;
use Illuminate\Database\Eloquent\Model;
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
                $this->createNewIngredient($ingredient['name'], $ingredient['available_stock_in_gram']);
            }

        } catch (\Exception $exception) { Log::error($exception); }
    }

    /**
     *
     * @param string $name
     * @param float $quantityOfStock
     * @return Model|null
     */
    public function createNewIngredient(string $name, float $quantityOfStock): ?Model
    {
        if (! $this->findSingleModelByKeyValuePair(['name' => $name])) {
            return $this->createModel(['name' => $name, 'available_stock_in_gram' => $quantityOfStock]);
        }

        return null;
    }
}
