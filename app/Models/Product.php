<?php

namespace App\Models;

use App\Utils\Utils;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var array
     */
    protected $guarded = [];

    public const CACHE_KEY = 'PRODUCT_CACHE';

    /**
     * @return HasMany
     */
    public function ingredients(): HasMany
    {
        return $this->hasMany(ProductIngredient::class, 'product_id');
    }

    /**
     * @param int $quantity
     * @return bool
     */
    public function isStockAvailableForOrder(int $quantity): bool
    {
        $status = false;
        $ingredients = $this->ingredients;
        if (count($ingredients)) {
            foreach ($ingredients as $ingredient) {
                $actual_quantity = Utils::convert_to_2_decimal_places($ingredient->qty_required * $quantity);
                if (! $ingredient->ingredient->is_out_of_stock && $ingredient->ingredient->available_stock_in_gram >= $actual_quantity) {
                    $status = true;
                }
            }
        }

        return $status;
    }

    /**
     * @return Attribute
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => ucfirst($value),
        );
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->attributes['id'];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->attributes['name'];
    }
}
