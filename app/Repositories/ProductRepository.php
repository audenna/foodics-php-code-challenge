<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Base\BaseRepositoryAbstract;

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


}
