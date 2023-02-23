<?php

namespace Tests\Unit;


use Database\Seeders\ProductSeeder;
use Tests\TestCase;

class ProductUnitTest extends TestCase
{

    /**
     *
     * @return void
     */
    public function test_default_product_can_be_seeded(): void
    {
        # Run the ProductSeeder...
        $this->seed(ProductSeeder::class);

        # asserts that at least, a product exists in the products table
        $this->assertDatabaseCount('products', 1);
    }

    /**
     *
     * @return void
     */
    public function test_product_has_burger_in_database(): void
    {
        # Run the ProductSeeder...
        $this->seed(ProductSeeder::class);

        # assert that product has burger
        $this->assertDatabaseHas('products', ['name' => 'Burger']);
    }

    /**
     *
     * @return void
     */
    public function test_product_can_be_created(): void
    {
        # Run the ProductSeeder...
        $this->seed(ProductSeeder::class);

        # create a new product and assert it's present
        $product = $this->productRepository->createNewProduct($this->faker->sentence(1));
        # assert that the database now has two products
        $this->assertDatabaseCount('products', 2);

        # assert that product has burger
        $this->assertDatabaseHas('products', ['name' => $product->getName()]);
    }
}
