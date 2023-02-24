<?php

namespace Tests\Unit;

use App\Http\Requests\OrderRequest;
use App\Models\Product;
use App\Services\Caches\ProductCache;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class OrderUnitTest extends TestCase
{

    /**
     *
     * @return void
     */
    public function test_order_should_contain_all_the_expected_validation_rules(): void
    {
        # run the DatabaseSeeder
        $this->seed();

        if (! Cache::has(Product::CACHE_KEY)) {
            ProductCache::refresh();
        }

        $request = new OrderRequest($this->orderRepository, $this->ingredientRepository,$this->productRepository);
        $this->assertEquals(
            [
                "products" => "required|array",
                'products.*.product_id' => "required|integer|exists:products,id",
                'products.*.quantity' => [
                    'required',
                    'integer',
                    function ($key, $value, $callback) {
                        # check that at least one Ingredient has enough to handle the request
                        if (! $this->ingredientRepository->countRecords('id', ['is_out_of_stock' => 0])) {
                            return $callback("Unable to proceed with your request at this time. Product ingredients are out of stock.");
                        }
                    }
                ]
            ],
            $request->rules()
        );
    }

    /**
     * @return void
     */
    public function test_customer_order_can_be_processed(): void
    {
        # trigger DatabaseSeeder to make all default records available
        $this->seed();

        # assert that the ProductIngredient records exists
        $this->assertDatabaseCount('product_ingredients', 3);

        # set an example payload to be posted
        $order = [
            "products" => [
                [
                    "product_id" => 1,
                    "quantity"   => 2
                ]
            ]
        ];

        # process Customer requests
        # This should be called from the Order Controller
        $this->orderRepository->processCustomerOrderRequest(
            $order['products'],
            $this->productRepository,
            $this->productIngredientRepository,
            $this->ingredientRepository,
            $this->usageRepository
        );

        # assert a new Order has been created
        $this->assertDatabaseCount('orders', 1);

        # assert that all the Ingredients got reduced
    }
}
