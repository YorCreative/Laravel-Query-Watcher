<?php

namespace YorCreative\QueryWatcher\Tests\Integration\Services;

use ReflectionClass;
use YorCreative\QueryWatcher\Services\ChannelService;
use YorCreative\QueryWatcher\Strategies\NotificationStrategy\Channels\Discord;
use YorCreative\QueryWatcher\Strategies\NotificationStrategy\Channels\Slack;
use YorCreative\QueryWatcher\Tests\TestCase;

class ChannelServiceTest extends TestCase
{
    /**
     * @test
     * @group Integration
     * @group Services
     */
    public function it_has_available_channels()
    {
        $class = new ReflectionClass(ChannelService::class);
        $protectedMethod = $class->getMethod('getChannels');
        $protectedMethod->setAccessible(true);

        $channels = $protectedMethod->invokeArgs(new ChannelService(), []);

        $this->assertCount(2, $channels);

        $channels->each(function ($channel) {
            $this->assertTrue(in_array($channel, [
                new Discord(),
                new Slack(),
            ]));
        });
    }
}
