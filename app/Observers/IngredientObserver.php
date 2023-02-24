<?php

namespace App\Observers;

use App\Models\Ingredient;
use App\Repositories\IngredientRepository;
use Illuminate\Support\Facades\Log;

class IngredientObserver
{

    /**
     * @return void
     */
    public function saved(): void
    {
        try {

            # get the Ingredient that has gone below 50%
//            $ingredient = (new IngredientRepository(new Ingredient()))->getTheIngredientThatHasGoneBelowTheThreshold();
//            if ($ingredient) {
//                # dispatch an email to notify the Customer
//
//            }

        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }
}
