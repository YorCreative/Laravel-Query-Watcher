<?php

namespace YorCreative\QueryWatcher\Tests;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use YorCreative\QueryWatcher\Models\QueryModel;
use YorCreative\QueryWatcher\Tests\Utility\TestBroadcasterServiceProvider;
use YorCreative\QueryWatcher\Tests\Utility\TestQueryWatcherServiceProvider;
use YorCreative\QueryWatcher\Tests\Utility\Traits\BroadcastAssertions;
use YorCreative\QueryWatcher\Tests\Utility\Traits\QueryAssertions;
use YorCreative\QueryWatcher\Transformers\QueryTransformer;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use QueryAssertions, BroadcastAssertions;

    public QueryExecuted $queryEvent;

    public string $sql;

    public float $time;

    public array $bindings;

    public QueryModel $queryModel;

    public function setUp(): void
    {
        parent::setUp();

        // additional setup
        $files = new Collection(File::files(dirname(__DIR__).'/tests/Utility/Migrations'));

        $files->each(function ($file) {
            $file = pathinfo($file);
            $migration = include $file['dirname'].'/'.$file['basename'];
            $migration->up();
        });

        $connection = DB::connection('testbench');
        $this->sql = 'select * from "Tests"';
        $this->time = 0.50;
        $this->bindings = [];
        $this->queryEvent = new QueryExecuted($this->sql, $this->bindings, $this->time, $connection);

        $this->queryModel = new QueryModel(QueryTransformer::transform($this->queryEvent));
    }

    /**
     * @param  Application  $app
     * @return string[]
     */
    protected function getPackageProviders($app): array
    {
        return [
            TestQueryWatcherServiceProvider::class,
            TestBroadcasterServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('broadcasting.default', 'test');
        $app['config']->set('broadcasting.connections.test', ['driver' => 'test']);
    }
}
