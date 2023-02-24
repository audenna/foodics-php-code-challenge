<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Base\BaseRepositoryAbstract;
use App\Utils\Utils;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderRepository extends BaseRepositoryAbstract
{

    /**
     * @var string
     */
    protected string $databaseTableName = 'orders';

    /**
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        parent::__construct($order, $this->databaseTableName);
    }

    /**
     *
     * @param array $customerOrders
     * @param ProductRepository $productRepository
     * @param ProductIngredientRepository $productIngredientRepository
     * @param IngredientRepository $ingredientRepository
     * @return void
     */
    public function processCustomerOrderRequest(
        array                       $customerOrders,
        ProductRepository           $productRepository,
        ProductIngredientRepository $productIngredientRepository,
        IngredientRepository        $ingredientRepository
    ): void
    {
        try {

            if (count($customerOrders)) {
                DB::transaction(function () use ($customerOrders, $productRepository, $productIngredientRepository, $ingredientRepository) {
                    foreach ($customerOrders as $order) {
                        # check that the product exists
                        if ($productRepository->findById($order['product_id'])) {

                            # save the order details
                            $new_order = $this->createModel([
                                'reference'  => Utils::generateUniqueReference($this->getAllTokens()),
                                'product_id' => $order['product_id'],
                                'quantity'   => $order['quantity']
                            ]);

                            # process the Ingredient stock update.
                            # This can also be added in the OrderObserver, if Transactional approach is not considered
                            /** @var Order $new_order */
                            $productIngredientRepository->processIngredientStockUpdatesByOrder($new_order, $ingredientRepository);
                        }
                    }
                });
            }

        } catch (\Exception $exception) { Log::error($exception); }
    }
}
