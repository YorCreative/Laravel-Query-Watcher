<?php

namespace YorCreative\QueryWatcher\Tests;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use YorCreative\QueryWatcher\Models\QueryModel;
use YorCreative\QueryWatcher\Tests\Utility\TestBroadcast;
use YorCreative\QueryWatcher\Tests\Utility\TestBroadcasterServiceProvider;
use YorCreative\QueryWatcher\Tests\Utility\TestQueryWatcherServiceProvider;
use YorCreative\QueryWatcher\Transformers\QueryTransformer;

class TestCase extends \Orchestra\Testbench\TestCase
{
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
     * @param $event
     * @param  null  $channels
     * @param  null  $count
     */
    public function assertEventBroadcasted($event, $channels = null, $count = null)
    {
        $broadcaster = resolve(TestBroadcast::class);

        $message = "Failed asserting that event '$event' was broadcasted";

        if (is_int($channels)) {
            $count = $channels;
            $channels = null;
        }

        if ($channels != null) {
            $message .= ' on channel ';
            if (is_array($channels)) {
                $message .= "s '".implode(', ', $channels)."'";
            } else {
                $message .= "'".$channels."'";
            }
        }

        if ($count != null) {
            $message .= ' '.$count.' times';
        }

        $broadcasts = $broadcaster->getBroadcasts();
        $broadcastCount = count($broadcasts);
        $evtStr = Str::plural('event', $broadcastCount);
        $message .= "\n$broadcastCount $evtStr was broadcasted: ".json_encode($broadcasts);

        $this->assertTrue($broadcaster->contains($event, $channels, $count), $message);
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
