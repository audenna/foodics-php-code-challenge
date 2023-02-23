<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Base\BaseRepositoryAbstract;
use Illuminate\Database\Eloquent\Model;
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
     *
     * @return void
     */
    public function seedDefaultProduct(): void
    {
        try {

            $this->createNewProduct('Burger');

        } catch (\Exception $exception) { Log::error($exception); }
    }

    /**
     * @param string $productName
     * @return Model|null
     */
    public function createNewProduct(string $productName): ?Model
    {
        if (! $this->findSingleModelByKeyValuePair(['name' => $productName])) {
            return $this->createModel(['name' => $productName]);
        }

        return null;
    }
}
