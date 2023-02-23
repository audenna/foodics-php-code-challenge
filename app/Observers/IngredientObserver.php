<?php

namespace App\Observers;

use App\Models\Ingredient;
use App\Utils\Utils;

class IngredientObserver
{

    /**
     *
     * @param Ingredient $ingredient
     * @return void
     */
    public function creating(Ingredient $ingredient): void
    {
        $ingredient->threshold_qty = Utils::getHalfTheAmountInGrams($ingredient->available_stock_in_gram);
    }
}
