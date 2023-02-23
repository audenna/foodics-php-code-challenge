<?php

namespace Tests;

use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\SQLiteBuilder;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Fluent;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        # configure foreign key issues with Sqlite
        $this->hotfixForeignKeySqlite();

        parent::setUp();

        $this->artisan('optimize:clear');
        $this->artisan('db:seed');
        $this->artisan('storage:link');

        Event::fake();
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
