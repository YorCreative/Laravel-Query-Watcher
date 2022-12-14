<?php

namespace YorCreative\QueryWatcher\Tests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use YorCreative\QueryWatcher\Tests\Utility\Models\DemoOwner;
use YorCreative\QueryWatcher\Transformers\AuthTransformer;

class AuthTransformerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        HTTP::fake();
        Event::fake();

        self::trackQueries();
    }

    /**
     * @test
     * @group Transformers
     * @group Unit
     */
    public function it_can_transform_authenticated_user_into_array()
    {
        $owner = DemoOwner::factory()->create();
        Auth::login($owner);

        $this->assertEquals(
            [
                'check' => true,
                'id' => $owner->id,
                'model' => $owner->getMorphClass(),
            ],
            AuthTransformer::transform()
        );

        $this->assertQueryCountMatches(1);
    }

    /**
     * @test
     * @group Transformers
     * @group Unit
     */
    public function it_can_transform_unauthenticated_user_into_array()
    {
        $this->assertEquals(
            [
                'check' => false,
                'id' => null,
                'model' => null,
            ],
            AuthTransformer::transform()
        );

        $this->assertNoQueriesExecuted();
    }
}
