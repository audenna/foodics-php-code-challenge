<?php

namespace App\Repositories;

use App\Models\Ingredient;
use App\Repositories\Base\BaseRepositoryAbstract;
use App\Utils\Utils;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
            return $this->createModel(
                [
                    'name'                    => $name,
                    'available_stock_in_gram' => $quantityOfStock,
                    'threshold_qty'           => Utils::getHalfTheAmountInGrams($quantityOfStock)
                ]
            );
        }

        return null;
    }

    /**
     *
     * @param int $ingredientId
     * @param float $totalRequiredQuantity
     * @return Model|null
     */
    public function updateAvailableStock(int $ingredientId, float $totalRequiredQuantity): ?Model
    {
        try {

            # check that the ingredient exists before updating
            $ingredient       = $this->findSingleModelByKeyValuePair(['id' => $ingredientId, 'is_out_of_stock' => 0]);
            if (! $ingredient || $totalRequiredQuantity > $ingredient->getAvailableStock()) return null;

            $new_quantity = $ingredient->getAvailableStock() - $totalRequiredQuantity;
            # update the Ingredient
            return $this->updateByIdAndGetBackRecord($ingredientId,
                [
                    'available_stock_in_gram' => $new_quantity,
                    'is_out_of_stock'         => $new_quantity == 0,
                ]
            );

        } catch (\Exception $exception) {
            Log::error($exception);

            return null;
        }
    }

    /**
     *
     * @param int $ingredientId
     * @return void
     */
    public function updateIsEmailSent(int $ingredientId): void
    {
        DB::table($this->databaseTableName)->where('id', $ingredientId)->update(['is_email_sent' => 1]);
    }

    /**
     *
     * @return Model|null
     */
    public function getTheIngredientThatHasGoneBelowTheThreshold(): ?Model
    {
        try {

            # get the Ingredient that's below 50%
            $ingredient = DB::select(
                "
                SELECT id FROM {$this->databaseTableName}
                WHERE available_stock_in_gram < threshold_qty
                AND (SELECT COUNT(*) FROM {$this->databaseTableName} WHERE is_email_sent = 1) = 0
                LIMIT 1"
            ) [0] ?? null;

            return $ingredient ? $this->findById($ingredient->id) : null;

        } catch (\Exception $exception) {
            Log::error($exception);

            return null;
        }
    }

}
