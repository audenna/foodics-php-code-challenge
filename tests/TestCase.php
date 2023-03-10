<?php

namespace Tests;

use App\Models\Ingredient;
use App\Models\IngredientOrderUsage;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductIngredient;
use App\Repositories\IngredientOrderUsageRepository;
use App\Repositories\IngredientRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductIngredientRepository;
use App\Repositories\ProductRepository;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\SQLiteBuilder;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Fluent;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase, WithFaker, HasFactory;

    /**
     * @var ProductRepository
     */
    protected ProductRepository $productRepository;

    /**
     * @var OrderRepository
     */
    protected OrderRepository $orderRepository;

    /**
     * @var IngredientRepository
     */
    protected IngredientRepository $ingredientRepository;

    /**
     * @var ProductIngredientRepository
     */
    protected ProductIngredientRepository $productIngredientRepository;

    /**
     * @var IngredientOrderUsageRepository
     */
    protected IngredientOrderUsageRepository $usageRepository;

    /**
     * @var string
     */
    protected string $api_base_path = 'api';

    /**
     * @return void
     */
    protected function setUp(): void
    {
        # configure foreign key issues with Sqlite
        $this->hotfixForeignKeySqlite();

        parent::setUp();

        $this->artisan("optimize:clear");

        Event::fake();

        # import all repository dependencies for Test cases
        $this->productRepository           = new ProductRepository(new Product());
        $this->orderRepository             = new OrderRepository(new Order());
        $this->ingredientRepository        = new IngredientRepository(new Ingredient());
        $this->productIngredientRepository = new ProductIngredientRepository(new ProductIngredient());
        $this->usageRepository             = new IngredientOrderUsageRepository(new IngredientOrderUsage());
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
//        parent::tearDown();
        //unset($_SERVER['DOCUMENT_ROOT']);
    }

    public function hotfixForeignKeySqlite()
    {
        Connection::resolverFor('sqlite', function ($connection, $database, $prefix, $config)
        {
            return new class($connection, $database, $prefix, $config) extends SQLiteConnection
            {
                public function getSchemaBuilder(): SQLiteBuilder
                {
                    if ($this->schemaGrammar === null) $this->useDefaultSchemaGrammar();

                    return new class($this) extends SQLiteBuilder
                    {
                        protected function createBlueprint($table, \Closure $callback = null): Blueprint
                        {
                            return new class($table, $callback) extends Blueprint
                            {
                                /**
                                 * @param $index
                                 * @return Fluent
                                 */
                                public function dropForeign($index): Fluent
                                {
                                    return new Fluent();
                                }

                                /**
                                 * @param $columns
                                 * @return Fluent
                                 */
                                public function dropColumn($columns): Fluent
                                {
                                    return new Fluent();
                                }

                                /**
                                 * @param $from
                                 * @param $to
                                 * @return Fluent
                                 */
                                public function renameColumn($from, $to): Fluent
                                {
                                    return new Fluent();
                                }
                            };
                        }
                    };
                }
            };
        });
    }
}
