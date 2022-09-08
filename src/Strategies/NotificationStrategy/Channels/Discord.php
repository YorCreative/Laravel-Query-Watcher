<?php

namespace YorCreative\QueryWatcher\Strategies\NotificationStrategy\Channels;

use Illuminate\Support\Facades\Http;
use YorCreative\QueryWatcher\Models\QueryModel;
use YorCreative\QueryWatcher\Strategies\NotificationStrategy\NotificationChannelInterface;

class Discord implements NotificationChannelInterface
{
    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return is_bool(config('querywatcher.channels.discord.enabled'))
            ? config('querywatcher.channels.discord.enabled')
            : false;
    }

    /**
     * @param  QueryModel  $model
     */
    public function notify(QueryModel $model): void
    {
        $fields = [];

        $fields = array_merge($fields, [
            ['name' => 'Bindings', 'value' => json_encode($model->getBindings())],
            ['name' => 'Execution Time', 'value' => json_encode($model->time).' ms'],
            ['name' => 'Connection', 'value' => $model->connection],
        ]);

        if ($model->trigger['action'] == 'Console') {
            $fields = array_merge($fields, [
                ['name' => 'Trigger', 'value' => $model->trigger['action']],
            ]);
        } else {
            $fields = array_merge($fields, [
                ['name' => 'Trigger', 'value' => $model->trigger['action'], 'inline' => true],
                ['name' => 'Method', 'value' => $model->trigger['context']['method'], 'inline' => true],
                ['name' => 'URL', 'value' => (string) $model->trigger['context']['url'], 'inline' => true],
            ]);

            $fields = array_merge($fields, [
                ['name' => 'Input', 'value' => json_encode($model->trigger['context']['input'])],
            ]);
        }

        Http::post(config('querywatcher.channels.discord.hook'), [
            'embeds' => [
                [
                    'title' => 'Captured SQL Query',
                    'description' => $model->sql,
                    'color' => '7506394',
                    'fields' => $fields,
                    'footer' => [
                        'text' => 'YorCreative/Laravel-QueryWatcher',
                    ],
                ],
            ],
        ]);
    }
}
