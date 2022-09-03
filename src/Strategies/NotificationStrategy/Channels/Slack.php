<?php

namespace YorCreative\QueryWatcher\Strategies\NotificationStrategy\Channels;

use Illuminate\Support\Facades\Http;
use YorCreative\QueryWatcher\Models\QueryModel;
use YorCreative\QueryWatcher\Strategies\NotificationStrategy\NotificationChannelInterface;

class Slack implements NotificationChannelInterface
{
    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return config('querywatcher.channels.slack.enabled');
    }

    /**
     * @param  QueryModel  $model
     */
    public function notify(QueryModel $model): void
    {
        // Core Base Information
        $payload = $this->buildCoreBaseEnrichment($model);

        // Contextual Information
        if ($model->trigger['action'] == 'Console') {
            $payload['blocks'] = $this->buildConsoleEnrichment($payload['blocks']);
        } else {
            $payload['blocks'] = $this->buildRequestEnrichment($payload['blocks'], $model);
        }

        // Fire off hook
        Http::retry(3)->contentType('application/json')->post(config('querywatcher.channels.slack.hook'), $payload);
    }

    /**
     * @note Slack Block Builder Reference
     * https://app.slack.com/block-kit-builder
     *
     * If you would like to improve your Slack message, see the block kit builder.
     */

    /**
     * @param  QueryModel  $model
     * @return \array[][]
     */
    protected function buildCoreBaseEnrichment(QueryModel $model): array
    {
        return [
            'blocks' => [
                [
                    'type' => 'divider',
                ],
                [
                    'type' => 'header',
                    'text' => [
                        'type' => 'plain_text',
                        'text' => 'Captured Query',
                    ],
                ],
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'plain_text',
                        'text' => $model->sql,
                    ],
                ],
                [
                    'type' => 'header',
                    'text' => [
                        'type' => 'plain_text',
                        'text' => 'Query Bindings',
                    ],
                ],
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'plain_text',
                        'text' => json_encode($model->getBindings()),
                    ],
                ],
                [
                    'type' => 'header',
                    'text' => [
                        'type' => 'plain_text',
                        'text' => 'Execution Time',
                    ],
                ],
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'plain_text',
                        'text' => json_encode($model->time).' ms',
                    ],
                ],
                [
                    'type' => 'header',
                    'text' => [
                        'type' => 'plain_text',
                        'text' => 'Connection',
                    ],
                ],
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'plain_text',
                        'text' => $model->connection,
                    ],
                ],
            ],
        ];
    }

    /**
     * @param  array  $payload
     * @return array
     */
    protected function buildConsoleEnrichment(array $payload): array
    {
        return array_merge($payload, [
            [
                'type' => 'header',
                'text' => [
                    'type' => 'plain_text',
                    'text' => 'Contextual Information',
                ],
            ],
            [
                'type' => 'context',
                'elements' => [
                    [
                        'type' => 'mrkdwn',
                        'text' => '*Trigger:* Console',
                    ],
                ],
            ],
        ]);
    }

    /**
     * @param  array  $payload
     * @param  QueryModel  $model
     * @return array
     */
    protected function buildRequestEnrichment(array $payload, QueryModel $model): array
    {
        return array_merge($payload, [
            [
                'type' => 'header',
                'text' => [
                    'type' => 'plain_text',
                    'text' => 'Contextual Information',
                ],
            ],
            [
                'type' => 'context',
                'elements' => [
                    [
                        'type' => 'mrkdwn',
                        'text' => '*Trigger:* '.$model->trigger['action'],
                    ],
                ],
            ],
            [
                'type' => 'context',
                'elements' => [
                    [
                        'type' => 'mrkdwn',
                        'text' => '*Method:* '.$model->trigger['context']['method'],
                    ],
                ],
            ],
            [
                'type' => 'context',
                'elements' => [
                    [
                        'type' => 'mrkdwn',
                        'text' => '*URL:* '.$model->trigger['context']['url'],
                    ],
                ],
            ],
            [
                'type' => 'context',
                'elements' => [
                    0 => [
                        'type' => 'mrkdwn',
                        'text' => '*Request Input:*',
                    ],
                ],
            ],
            [
                'type' => 'section',
                'text' => [
                    'type' => 'mrkdwn',
                    'text' => json_encode($model->trigger['context']['input']),
                ],
            ],
        ]);
    }
}
