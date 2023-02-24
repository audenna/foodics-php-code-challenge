<?php

namespace Tests\Feature;


use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class OrderFeatureTest extends TestCase
{

    /**
     * A basic feature test example.
     */
    public function test_base_api_url_can_be_accessed(): void
    {
        $response = $this->get($this->api_base_path);

        # assert that the url exists
        $response->assertStatus(200);

        # assert that it's a json response
        $response->assertExactJson(
            [
                'header'  => 'Alert',
                'status'  => true,
                'message' => 'Welcome to the Foodics API base url.',
                'data'    => null
            ],
        );
    }

    /**
     *
     * {
        "products": [
            {
                "product_id": 1,
                "quantity": 2,
            }
        ]
     }
     * @return void
     */
    public function test_order_can_be_successfully_created(): void
    {
        # trigger Database seeder
        $this->seed();

        # assert that the product and ingredients already exists
        $this->assertDatabaseCount('product_ingredients', 3);

        # set the order payload
        $payload = [
            "products" => [
                [
                    "product_id" => 1,
                    "quantity"   => 2
                ]
            ]
        ];

        # try to make a post request to the Order endpoint
        $this->json('POST', "{$this->api_base_path}/orders", $payload)
            ->assertStatus(200)
            ->json();
    }
}
