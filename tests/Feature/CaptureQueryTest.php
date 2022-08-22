<?php

namespace YorCreative\QueryWatcher\Tests\Feature;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use YorCreative\QueryWatcher\Events\QueryEvent;
use YorCreative\QueryWatcher\Listeners\QueryListener;
use YorCreative\QueryWatcher\Tests\TestCase;
use YorCreative\QueryWatcher\Tests\Utility\Models\Test;

class CaptureQueryTest extends TestCase
{
    /**
     * @test
     * @group Feature
     */
    public function it_can_capture_a_query()
    {
        HTTP::fake();

        (new Test())
            ->newQuery()
            ->get();

        $this->assertEventBroadcasted(
            'query.event'
        );
    }

    /**
     * @test
     * @group Feature
     */
    public function it_is_listening_for_query_event()
    {
        Event::fake();

        Event::assertListening(QueryEvent::class, QueryListener::class);
    }
}
