<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductObserver
{

    /**
     * A Cache implementation of products is triggered when there's either an update or insertion in the Product Model.
     *
     * @param Product $product
     * @return void
     */
    public function saved(Product $product): void
    {
        try {

            # trigger a Product Cache Service


        } catch (\Exception $exception) { Log::error($exception); }
    }
}
