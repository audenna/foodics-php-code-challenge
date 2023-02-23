<?php

namespace App\Repositories;

use App\Models\ProductIngredient;
use App\Repositories\Base\BaseRepositoryAbstract;

class ProductIngredientRepository extends BaseRepositoryAbstract
{

    /**
     * @var string
     */
    protected string $databaseTableName = 'product_ingredients';

    /**
     *
     * @param ProductIngredient $productIngredient
     */
    public function __construct(ProductIngredient $productIngredient)
    {
        parent::__construct($productIngredient, $this->databaseTableName);
    }


}
