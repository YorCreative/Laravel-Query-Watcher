<?php

namespace YorCreative\QueryWatcher\Tests\Unit\Strategies;

use Illuminate\Support\Facades\Log;
use YorCreative\QueryWatcher\Models\QueryModel;
use YorCreative\QueryWatcher\Strategies\NotificationStrategy\NotificationChannelInterface;
use YorCreative\QueryWatcher\Strategies\NotificationStrategy\NotificationStrategy;
use YorCreative\QueryWatcher\Tests\TestCase;

class NotificationStrategyTest extends TestCase
{
    /**
     * @test
     * @group Unit
     * @group Strategies
     */
    public function it_can_add_channels_to_notification_strategy()
    {
        $aChannel = new Channel;

        $notificationStrategy = (new NotificationStrategy());
        $notificationStrategy->setChannel($aChannel);

        $this->assertCount(1, $notificationStrategy->getChannels());
    }
}

class Channel implements NotificationChannelInterface
{
    public function isEnabled(): bool
    {
        return true;
    }

    public function notify(QueryModel $model): void
    {
        Log::info('notify');
    }
}
