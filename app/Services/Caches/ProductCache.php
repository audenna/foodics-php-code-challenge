<?php

namespace App\Services\Caches;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProductCache
{

    /**
     * @return void
     */
    protected static function hydrate(): void
    {
        # clear the cached data
        Cache::forget(Product::CACHE_KEY);
        # cache updated data back
        Cache::put(Product::CACHE_KEY, Product::with('ingredients')->sharedLock()->get());
    }

    /**
     * @return void
     */
    public static function refresh(): void
    {
        static::hydrate();
    }

    /**
     *
     * @return Collection
     */
    public static function get(): Collection
    {
        # if the cached key is not available, flush and reload back
        if (! Cache::has(Product::CACHE_KEY)) {
            static::hydrate();
        }

        # load up the Cashed data
        return Cache::get(Product::CACHE_KEY);
    }

    /**
     *
     * @param string $columnName
     * @param string $value
     * @return Product|null
     */
    public static function findByColumnAndValue(string $columnName, string $value): ?Product
    {
        return static::get()->where($columnName, $value)->first() ?? null;
    }
}
