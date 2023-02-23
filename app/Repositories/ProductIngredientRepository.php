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

            $burger_product = $productRepository->findSingleByWhereClause(['name' => 'Burger']);
            if ($burger_product) {
                foreach ($ingredients as $ingredient) {
                    $this->saveRecordIfNotCreated($burger_product->getId(), $ingredient['name'], $ingredient['qty_required'], $ingredientRepository);
                }
            }

        } catch (\Exception $exception) { Log::error($exception); }
    }

    /**
     *
     * @param int $productId
     * @param string $ingredientName
     * @param float $quantityRequired
     * @param IngredientRepository $ingredientRepository
     * @return void
     */
    private function saveRecordIfNotCreated(
        int                  $productId,
        string               $ingredientName,
        float                $quantityRequired,
        IngredientRepository $ingredientRepository
    ): void
    {
        try {

            $ingredient = $ingredientRepository->findSingleByWhereClause(['name' => $ingredientName]);
            if ($ingredient && ! $this->findSingleByWhereClause(['product_id' => $productId, 'ingredient_id' => $ingredient->getId()])) {
                $this->createModel(
                    [
                        'product_id'    => $productId,
                        'ingredient_id' => $ingredient->getId(),
                        'qty_required'  => $quantityRequired,
                    ]
                );
            }

        } catch (\Exception $exception) { Log::error($exception); }
    }
}