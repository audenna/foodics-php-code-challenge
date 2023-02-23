<?php

namespace Tests\Unit;

use App\Utils\Utils;
use Database\Seeders\IngredientSeeder;
use Tests\TestCase;

class IngredientUnitTest extends TestCase
{

    /**
     *
     * @return void
     */
    public function test_default_ingredients_can_be_seeded(): void
    {
        # Run the ProductSeeder...
        $this->seed(IngredientSeeder::class);

        # asserts that three ingredients already exists
        $this->assertDatabaseCount('ingredients', 3);
    }

    /**
     *
     * @return void
     */
    public function test_ingredients_has_seeded_names(): void
    {
        # Run the ProductSeeder...
        $this->seed(IngredientSeeder::class);

        # assert that ingredients stated exists
        $this->assertDatabaseHas('ingredients', ['name' => 'Beef']);
        $this->assertDatabaseHas('ingredients', ['name' => 'Cheese']);
        $this->assertDatabaseHas('ingredients', ['name' => 'Onion']);
    }

    /**
     *
     * @return void
     */
    public function test_ingredients_can_be_created(): void
    {
        # create a new product and assert it's present
        $ingredient = $this->ingredientRepository->createNewIngredient(
            $this->faker->sentence(1),
            Utils::convert_to_2_decimal_places($this->faker->randomFloat())
        );

        # assert that the database has been populated
        $this->assertDatabaseCount('ingredients', 1);

        # assert that the database now has the newly created ingredient
        $this->assertDatabaseHas('ingredients', ['name' => $ingredient->getName()]);
    }
}
