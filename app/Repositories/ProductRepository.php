<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Base\BaseRepositoryAbstract;
use Illuminate\Support\Facades\Log;

class ProductRepository extends BaseRepositoryAbstract
{

    /**
     * @var string
     */
    protected string $databaseTableName = 'products';

    /**
     *
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        parent::__construct($product, $this->databaseTableName);
    }

    /**
     * @return void
     */
    public function seedDefaultProduct(): void
    {
        try {

            $name = 'Burger';
            if (! $this->findSingleByWhereClause(['name' => $name])) {
                $this->createModel(['name' => $name]);
            }

        } catch (\Exception $exception) { Log::error($exception); }
    }
}
