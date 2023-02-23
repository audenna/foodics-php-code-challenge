<?php

namespace Tests\Unit;

use App\Models\ProductIngredient;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ProductIngredientUnitTest extends TestCase
{

    /**
     *
     * @return void
     */
    public function test_default_product_ingredients_can_be_seeded(): void
    {
        # Run the DatabaseSeeder...
        $this->seed();

        # asserts that three ingredients already exists
        $this->assertDatabaseCount('product_ingredients', 3);
    }

    /**
     *
     * @return void
     */
    public function test_burger_as_a_product_has_three_ingredients(): void
    {
        # Run the DatabaseSeeder...
        $this->seed();

        # get the id of the burger from the product table
        $burger = $this->productRepository->findSingleModelByKeyValuePair(['name' => 'Burger']);

        # assert that the burger object does not return null
        $this->assertNotNull($burger);

        # assert that Burger has three ingredients

    }
}
