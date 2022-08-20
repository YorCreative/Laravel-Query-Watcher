<?php

namespace YorCreative\QueryWatcher\Tests\Utility;

use Illuminate\Broadcasting\Broadcasters\Broadcaster;
use Illuminate\Http\Request;

class TestBroadcast extends Broadcaster
{
    private array $broadcasts = [];

    /**
     * {@inheritdoc}
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {
        if (! array_key_exists($event, $this->broadcasts)) {
            $this->broadcasts[$event] = collect([]);
        }

        $this->broadcasts[$event][] = [
            'channels' => $channels,
            'event' => $event,
            'payload' => $payload,
        ];
    }

    public function getBroadcasts(): array
    {
        return $this->broadcasts;
    }

    /**
     * {@inheritdoc}
     */
    public function auth($request)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function validAuthenticationResponse($request, $result)
    {
    }

    /**
     * Authenticate the incoming request for a given channel.
     *
     * @param  Request  $request
     * @return mixed
     */

    /**
     * Return if the broadcaster contains event.
     *
     * @param  string  $event
     * @param  null  $channels
     * @param  null  $count
     * @param  array|null  $payload
     * @return bool
     */
    public function contains(string $event, $channels = null, $count = null, array $payload = null): bool
    {
        if (is_int($channels)) {
            $count = $channels;
            $channels = null;
        }

        if (! array_key_exists($event, $this->broadcasts)) {
            return false;
        }

        $eventBroadcasts = $this->broadcasts[$event];

        if ($count != null && count($eventBroadcasts) != $count) {
            return false;
        }

        if ($channels != null) {
            $last = $eventBroadcasts->last();

            if (! $this->broadcastContainsAllChannels($last, $channels)) {
                return false;
            }
        }

        //@TODO: Allow to test for payload

        return true;
    }

    private function broadcastContainsAllChannels(array $broadcast, $channels): bool
    {
        if (! is_array($channels)) {
            return $this->broadcastContainsChannel($broadcast, $channels);
        }

        foreach ($channels as $channel) {
            if (! $this->broadcastContainsChannel($broadcast, $channel)) {
                return false;
            }
        }

        return true;
    }

    private function broadcastContainsChannel(array $broadcast, string $channel): bool
    {
        return in_array($channel, $broadcast['channels']);
    }
}
