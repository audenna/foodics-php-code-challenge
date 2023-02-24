<?php

namespace Tests\Unit;


use App\Utils\Utils;
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
    public function test_burger_as_a_product_have_three_ingredients(): void
    {
        # Run the DatabaseSeeder...
        $this->seed();

        # get the id of the burger from the product table
        $burger = $this->productRepository->findSingleModelByKeyValuePair(['name' => 'Burger']);

        # assert that the burger object does not return null
        $this->assertNotNull($burger);

        # assert that Burger has three ingredients
        $this->assertEquals(3, $this->productIngredientRepository->countRecords('id', ['product_id' => $burger->getId()]));
    }

    /**
     *
     * @return void
     */
    public function test_product_ingredients_can_be_created(): void
    {
        # create new product
        $product    = $this->productRepository->createNewProduct($this->faker()->sentence(1));
        # assert that the new product has been created
        $this->assertDatabaseHas('products', ['name' => $product->getName()]);

        # create a new ingredient
        $ingredient = $this->ingredientRepository->createNewIngredient($this->faker()->sentence(1), Utils::convert_to_2_decimal_places($this->faker()->randomFloat()));
        # assert that the new ingredient has been created
        $this->assertDatabaseHas('ingredients', ['name' => $ingredient->getName()]);

        # create a new product_ingredient record
        $this->productIngredientRepository->saveRecordIfNotCreated($product->getId(), $ingredient->getId(), $this->faker->numberBetween(10, 50));

        # assert that the new product ingredient has been created
        $this->assertDatabaseCount('product_ingredients', 1);
    }
}
