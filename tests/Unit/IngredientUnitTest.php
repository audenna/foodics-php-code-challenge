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

        # assert that the ingredients stated exists
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

    /**
     *
     * @return void
     */
    public function test_an_ingredient_can_be_out_of_stock(): void
    {
        # create a new Ingredient
        $stock      = $this->faker->numberBetween(100, 500);
        $ingredient = $this->ingredientRepository->createNewIngredient($this->faker->sentence(1), $stock);
        # assert that the Ingredient was created
        $this->assertNotNull($ingredient);

        # assert that the new Ingredient cab be found
        $this->assertDatabaseHas('ingredients', ['name' => $ingredient->getName()]);

        # consume the same quantity of stock available
        $this->ingredientRepository->updateAvailableStock($ingredient->getId(), $stock);
        # assert that the Ingredient is out of stock
        $this->assertTrue($this->ingredientRepository->findById($ingredient->getId())->isOutOfStock());
    }

    /**
     *
     * @return void
     */
    public function test_an_ingredient_can_reached_below_its_threshold(): void
    {
        # trigger the IngredientSeeder
        $this->seed(IngredientSeeder::class);

        # assert that the ingredient model has been populated
        $this->assertDatabaseCount('ingredients', 3);

        # get the Beef Ingredient
        $ingredient = $this->ingredientRepository->findSingleModelByKeyValuePair(['name' => 'Beef']);
        # assert that the Ingredient is available
        $this->assertNotNull($ingredient);

        # consume less than 50 % of the Ingredients
        $added_amount_of_stock = 10;
        $ingredient = $this->ingredientRepository->updateAvailableStock($ingredient->getId(), $ingredient->getThreshold() + $added_amount_of_stock);
        # assert that the Stock has gone below its threshold (50%)
        $this->assertTrue($ingredient->getAvailableStock() < $ingredient->getThreshold());
    }

    /**
     *
     * @return void
     */
    public function test_an_email_can_be_sent_when_an_ingredient_has_gone_below_its_threshold(): void
    {
        # trigger the IngredientSeeder
        $this->seed(IngredientSeeder::class);

        # assert that the ingredient model has been populated
        $this->assertDatabaseCount('ingredients', 3);

        # get the Beef Ingredient
        $ingredient = $this->ingredientRepository->findSingleModelByKeyValuePair(['name' => 'Beef']);
        # assert that the Ingredient is available
        $this->assertNotNull($ingredient);

        # consume less than 50 % of the Ingredients
        $added_amount_of_stock = 10;
        $ingredient = $this->ingredientRepository->updateAvailableStock($ingredient->getId(), $ingredient->getThreshold() + $added_amount_of_stock);
        # assert that the Stock has gone below its threshold (50%)
        $this->assertTrue($ingredient->getAvailableStock() < $ingredient->getThreshold());

        # get the Ingredient that has gone below 50%
        $ingredient_with_less_stock = $this->ingredientRepository->getTheIngredientThatHasGoneBelowTheThreshold();
        # assert that an Ingredient that has gone below 50% can be retrieved
        $this->assertNotNull($ingredient_with_less_stock);
    }

    /**
     * This tests that an email can be sent when an Ingredient has gone below 50%
     *
     * @return void
     */
    public function test_email_can_be_sent_when_an_ingredient_has_gone_below_its_threshold(): void
    {
        # trigger the IngredientSeeder
        $this->seed(IngredientSeeder::class);

        # assert that the ingredient model has been populated
        $this->assertDatabaseCount('ingredients', 3);

        # get any Ingredient between a sated range of numbers
        $ingredient_id = $this->faker->numberBetween(1, 3);
        $this->assertDatabaseHas('ingredients', ['id' => $ingredient_id]);
        $ingredient = $this->ingredientRepository->findSingleModelByKeyValuePair(['id' => $ingredient_id]);
        $this->assertNotNull($ingredient);

        # consume less than 50 % of the Ingredients
        $ingredient = $this->ingredientRepository->updateAvailableStock($ingredient_id, $ingredient->getThreshold() + 10);
        # assert that the Stock has gone below its threshold (50%)
        $this->assertTrue($ingredient->getAvailableStock() < $ingredient->getThreshold());

        # send an email notification
        $this->orderRepository->sendEmailNotificationIfAnIngredientHasGoneLessOfTheThreshold($this->ingredientRepository);
        # get the Ingredient that has gone below 50%
        $ingredient_with_less_stock = $this->ingredientRepository->getTheIngredientThatHasGoneBelowTheThreshold();
        # assert that an Ingredient that has gone below 50% has received the is_email_sent update
        $this->assertNull($ingredient_with_less_stock);
    }
}
