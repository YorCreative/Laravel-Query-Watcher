<?php

namespace YorCreative\QueryWatcher\Tests\Unit\Strategies\Channels;

use Illuminate\Support\Facades\Http;
use YorCreative\QueryWatcher\Strategies\NotificationStrategy\Channels\Slack;
use YorCreative\QueryWatcher\Tests\TestCase;

class SlackTest extends TestCase
{
    public Slack $slackChannel;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->slackChannel = new Slack();
    }

    /**
     * @test
     * @group Unit
     * @group Channels
     * @group Strategies
     */
    public function it_can_send_http_request_to_slack()
    {
        Http::fake();

        $this->slackChannel->notify($this->queryModel);

        Http::assertSentCount(1);
    }

    /**
     * @test
     * @group Unit
     * @group Channels
     * @group Strategies
     */
    public function it_can_determine_that_slack_channel_is_enabled()
    {
        app()['config']->set('querywatcher.channels.slack.enabled', true);
        $this->assertTrue($this->slackChannel->isEnabled());
    }

    /**
     * @test
     * @group Unit
     * @group Channels
     * @group Strategies
     */
    public function it_can_determine_that_slack_channel_is_not_enabled()
    {
        app()['config']->set('querywatcher.channels.slack.enabled', false);
        $this->assertFalse($this->slackChannel->isEnabled());
    }
}
