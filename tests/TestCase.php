<?php

namespace Tests;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\IngredientRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Services\Caches\ProductCache;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\SQLiteBuilder;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Fluent;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase, WithFaker;

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
        $this->productRepository    = new ProductRepository(new Product());
        $this->orderRepository      = new OrderRepository(new Order());
        $this->ingredientRepository = new IngredientRepository(new Ingredient());
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
