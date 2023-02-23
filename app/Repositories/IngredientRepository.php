<?php

namespace App\Repositories;

use App\Models\Ingredient;
use App\Repositories\Base\BaseRepositoryAbstract;

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


}
