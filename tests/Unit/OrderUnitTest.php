<?php

namespace Tests\Unit;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Services\Caches\ProductCache;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
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

        $request = new OrderRequest($this->orderRepository);
        $this->assertEquals(
            [
                "products" => "required|array",
                'products.*.product_id' => "required|integer|exists:products,id",
                'products.*.quantity' => [
                    'required',
                    'integer',
                    function ($key, $value, $callback) {
                        # check that the product still has enough quantity in stock

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
        $this->orderRepository->processCustomerOrderRequest($order['products']);

        # assert a new Order has been created
        $this->assertDatabaseCount('orders', 1);
    }
}
