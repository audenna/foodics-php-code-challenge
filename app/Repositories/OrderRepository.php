<?php

namespace App\Repositories;

use App\Jobs\SendEmailJob;
use App\Models\Ingredient;
use App\Models\Order;
use App\Repositories\Base\BaseRepositoryAbstract;
use App\Utils\Utils;
use Carbon\Carbon;
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

    /***
     *
     * @param int $productId
     * @param int $quantity
     * @return Model
     */
    public function creatOrder(int $productId, int $quantity): Model
    {
        return $this->createModel([
            'reference'  => Utils::generateUniqueReference($this->getAllTokens()),
            'product_id' => $productId,
            'quantity'   => $quantity
        ]);
    }

    /**
     *
     * @param array $customerOrders
     * @param ProductRepository $productRepository
     * @param ProductIngredientRepository $productIngredientRepository
     * @param IngredientRepository $ingredientRepository
     * @param IngredientOrderUsageRepository $usageRepository
     * @return void
     */
    public function processCustomerOrderRequest(
        array                          $customerOrders,
        ProductRepository              $productRepository,
        ProductIngredientRepository    $productIngredientRepository,
        IngredientRepository           $ingredientRepository,
        IngredientOrderUsageRepository $usageRepository
    ): void
    {
        try {

            if (count($customerOrders)) {
                DB::transaction(function () use ($customerOrders, $productRepository, $productIngredientRepository, $ingredientRepository, $usageRepository) {
                    foreach ($customerOrders as $order) {
                        # check that the product exists
                        if ($productRepository->findById($order['product_id'])) {
                            # save the order details
                            $new_order = $this->creatOrder($order['product_id'], $order['quantity']);

                            # process the Ingredient stock update.
                            # This can also be added in the OrderObserver, if Transactional approach is not considered
                            /** @var Order $new_order */
                            $productIngredientRepository->processIngredientStockUpdatesByOrder($new_order, $ingredientRepository, $usageRepository);
                        }
                    }
                    # dispatch an email if any of the ingredients has gone below 50%
                    $this->sendEmailNotificationIfAnIngredientHasGoneLessOfTheThreshold($ingredientRepository);
                });
            }

        } catch (\Exception $exception) { Log::error($exception); }
    }

    /**
     *
     * @param IngredientRepository $ingredientRepository
     * @return void
     */
    public function sendEmailNotificationIfAnIngredientHasGoneLessOfTheThreshold(IngredientRepository $ingredientRepository): void
    {
        try {

            # get the Ingredient that has gone below 50%
            $ingredient_with_less_stock = $ingredientRepository->getTheIngredientThatHasGoneBelowTheThreshold();
            if ($ingredient_with_less_stock) {
                Log::alert($ingredient_with_less_stock);
                # trigger a Job to handle the email dispatch and delay for 2 seconds
                /** @var Ingredient $ingredient_with_less_stock */
                dispatch(new SendEmailJob($ingredient_with_less_stock))->delay(Carbon::now()->addSeconds(2));
                # update the ingredient to stop any further email dispatcher
                $ingredientRepository->updateIsEmailSent($ingredient_with_less_stock->getId());
            }

        } catch (\Exception $exception) { Log::error($exception); }
    }
}
