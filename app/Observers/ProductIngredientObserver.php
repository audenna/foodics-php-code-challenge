<?php

namespace App\Observers;

use App\Models\ProductIngredient;
use App\Services\Caches\ProductCache;
use Illuminate\Support\Facades\Log;

class ProductIngredientObserver
{

    /**
     *
     * @param ProductIngredient $productIngredient
     * @return void
     */
    public function saved(ProductIngredient $productIngredient): void
    {
        try {

            # save the product in a cache
            ProductCache::refresh();

        } catch (\Exception $exception) { Log::error($exception); }
    }
}
