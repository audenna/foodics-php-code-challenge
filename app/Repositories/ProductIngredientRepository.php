<?php

namespace App\Repositories;

use App\Models\ProductIngredient;
use App\Repositories\Base\BaseRepositoryAbstract;
use Illuminate\Support\Facades\Log;

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

    /**
     *
     * @param ProductRepository $productRepository
     * @param IngredientRepository $ingredientRepository
     * @return void
     */
    public function seedDefaultProductIngredients(ProductRepository $productRepository, IngredientRepository $ingredientRepository): void
    {
        try {

            # set the default ingredients and volumes to be seeder
            $ingredients = [
                [
                    'name' => 'Beef',
                    'qty_required' => 150,
                ],
                [
                    'name' => 'Cheese',
                    'qty_required' => 30,
                ],
                [
                    'name' => 'Onion',
                    'qty_required' => 20,
                ],
            ];

            $burger_product = $productRepository->findSingleModelByKeyValuePair(['name' => 'Burger']);
            if ($burger_product) {
                foreach ($ingredients as $ingredient) {
                    $ingredient = $ingredientRepository->findSingleModelByKeyValuePair(['name' => $ingredient['name']]);
                    if ($ingredient) {
                        $this->saveRecordIfNotCreated($burger_product->getId(), $ingredient->getId(), $ingredient['qty_required']);
                    }
                }
            }

        } catch (\Exception $exception) { Log::error($exception); }
    }

    /**
     *
     * @param int $productId
     * @param string $ingredientId
     * @param float $quantityRequired
     * @return void
     */
    public function saveRecordIfNotCreated(int $productId, string $ingredientId, float  $quantityRequired): void
    {
        try {

            if (! $this->findSingleModelByKeyValuePair(['product_id' => $productId, 'ingredient_id' => $ingredientId])) {
                $this->createModel(
                    [
                        'product_id'    => $productId,
                        'ingredient_id' => $ingredientId,
                        'qty_required'  => $quantityRequired,
                    ]
                );
            }

        } catch (\Exception $exception) { Log::error($exception); }
    }
}
