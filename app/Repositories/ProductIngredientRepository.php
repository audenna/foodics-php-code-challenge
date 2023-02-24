<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\ProductIngredient;
use App\Repositories\Base\BaseRepositoryAbstract;
use App\Utils\Utils;
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
            $product_ingredients = [
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
                foreach ($product_ingredients as $product_ingredient) {
                    $ingredient = $ingredientRepository->findSingleModelByKeyValuePair(['name' => $product_ingredient['name']]);
                    if ($ingredient) {
                        $this->saveRecordIfNotCreated($burger_product->getId(), $ingredient->getId(), $product_ingredient['qty_required']);
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

    /**
     *
     * @param Order $order
     * @param IngredientRepository $ingredientRepository
     * @param IngredientOrderUsageRepository $usageRepository
     * @return void
     */
    public function processIngredientStockUpdatesByOrder(
        Order                          $order,
        IngredientRepository           $ingredientRepository,
        IngredientOrderUsageRepository $usageRepository
    ): void
    {
        try {

            # get all the records based on the product Id supplied
            $product_ingredients    = $this->findRecordsByColumnAndValue('product_id', $order->getProductId());
            if (count($product_ingredients)) {
                foreach ($product_ingredients as $product_ingredient) {
                    # update the Ingredients based on the quantity requested
                    $total_quantity = Utils::convert_to_2_decimal_places($order->getQuantity() * $product_ingredient->getQtyRequired());
                    # update the quantity used
                    $ingredientRepository->updateAvailableStock($product_ingredient->getIngredientId(), $total_quantity);
                    # log the Ingredient usage
                    $usageRepository->saveLog($order->getId(), $product_ingredient->getIngredientId(), $total_quantity);
                }
            }

        } catch (\Exception $exception) { Log::error($exception); }
    }
}
