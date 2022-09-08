<br />
<br />

<div align="center">
  <a href="https://github.com/YorCreative">
    <img src="content/logo.png" alt="Logo" width="128" height="128">
  </a>
</div>

<h3 align="center">Laravel Query Watcher</h3>

<div align="center">
<a href="https://github.com/YorCreative/Laravel-Query-Watcher/stargazers"><img alt="GitHub stars" src="https://img.shields.io/github/stars/YorCreative/Laravel-Query-Watcher"></a>
<a href="https://github.com/YorCreative/Laravel-Query-Watcher/issues"><img alt="GitHub issues" src="https://img.shields.io/github/issues/YorCreative/Laravel-Query-Watcher"></a>
<a href="https://github.com/YorCreative/Laravel-Query-Watcher/network"><img alt="GitHub forks" src="https://img.shields.io/github/forks/YorCreative/Laravel-Query-Watcher"></a>
<a href="https://github.com/YorCreative/Laravel-Query-Watcher/actions/workflows/phpunit.yml"><img alt="PHPUnit" src="https://github.com/YorCreative/Laravel-Query-Watcher/actions/workflows/phpunit.yml/badge.svg"></a>
</div>

<br />


A Laravel package that provides configurable application query capturing & monitoring.

## Installation

install the package via composer:

```bash
composer require YorCreative/Laravel-Query-Watcher
```

Publish the packages assets.

```bash
php artisan vendor:publish --provider="YorCreative\QueryWatcher\QueryWatcherServiceProvider"
```

## Usage

### Configuration

Adjust the configuration file to suite your application.

```php
[
    // Do you want to capture queries?
    'enabled' => env('QUERY_WATCH_ENABLED', true), 
    
    // Token used for Authenticating Private Broadcast Channel
    'token' => env('QUERY_WATCH_TOKEN', 'change_me'), 
    'scope' => [
        'time_exceeds_ms' => [
            // Do you want to capture everything or only slow queries?
            'enabled' => env('QUERY_WATCH_SCOPE_TIME_ENABLED', true), 
            
            // The number of milliseconds it took to execute the query.
            'threshold' => env('QUERY_WATCH_SCOPE_TIME_THRESHOLD', 500), 
        ],
        'context' => [
            'auth_user' => [
                // Do you want to know context of the authenticated user when query is captured?
                'enabled' => env('QUERY_WATCH_SCOPE_CONTEXT_AUTH_ENABLED', true),
                 
                // How long do you want the session_id/authenticated user cached for? 
                // without this cache, your application will infinite loop because it will capture
                // the user query and loop.
                // See closed Issue #1 for context.
                'ttl' => env('QUERY_WATCH_SCOPE_CONTEXT_AUTH_TTL', 300), 
            ],
            'trigger' => [
                // Do you want to know what triggered the query?
                // i.e Console command or Request
                'enabled' => env('QUERY_WATCH_SCOPE_TRIGGER_ENABLED', true), 
            ],
        ],
        'ignorable_tables' => [
            // Do you want to capture queries on specific tables?
            // If you are utilizing the database queue driver, you need to
            // ignore the jobs table, or you'll potentially get infinite capture loops.
            'jobs',
            'failed_jobs'
        ],
        'ignorable_statements' => [
            // Do you want to ignore specific SQL statements?
            'create' 
        ]
    ],
    'listener' => [ 
        // Channel notifications are queued
        // Define what connection to use.
        'connection' => 'sync',
        
        //  Define what queue to use 
        'queue' => 'default',
        
        // Do you want to delay the notifications at all? 
        'delay' => null, 
    ],
    'channels' => [ // Where to send notifications?
        'discord' => [ 
            // Do you want discord webhook notifications?
            'enabled' => env('QUERY_WATCH_CHANNEL_DISCORD_ENABLED', false),
            
            // Discord Web-hook URL 
            'hook' => env('DISCORD_HOOK', 'please_fill_me_in'), 
        ],
        'slack' => [ 
            // Do you want Slack webhook notifications?
            'enabled' => env('QUERY_WATCH_CHANNEL_SLACK_ENABLED', false),
            
            // Slack Web-hook URL
            'hook' => env('SLACK_HOOK', 'please_fill_me_in'), 
        ],
    ]
]
```

### Broadcasting

All captured queries will broadcast on a private channel as the primary monitoring method. The QueryEvent that is
broadcasting is using your
applications [broadcast configuration](https://laravel.com/docs/9.x/broadcasting#configuration).

```php
    /**
     * Get the channels the event should broadcast on.
     *
     * @return PrivateChannel
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('query.event.'. config('querywatcher.token'));
    }

    /**
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'query.event';
    }
```

### Slack Notification Channel

To utilize Slack Notifications, you will need to [create a webhook](https://api.slack.com/messaging/webhooks#create_a_webhook) for one of your Slack Channels. Once you have your
webhook url, add the following variable to your .env file.

```dotenv
SLACK_HOOK=<hook>
```

Once you have done this, you can enable Slack Notifications in the configuration file.

### Discord Notification Channel

Get a webhook URL from discord in the channel you want to receive your notifications in by
reading [Discords Introduction to Webhook Article](https://support.discord.com/hc/en-us/articles/228383668-Intro-to-Webhooks)
. Once you have your webhook url, add the following variable to your `.env` file.

```dotenv
DISCORD_HOOK=<hook>
```

Once you have done this, you can enable Discord Notifications in the configuration file.

### Wiki Documentation

- [Notification Channels Wiki](https://github.com/YorCreative/Laravel-Query-Watcher/wiki/Notification-Channels)
- [Screenshots](https://github.com/YorCreative/Laravel-Query-Watcher/wiki/Screenshots)

## Testing

```bash
composer test
```

## Credits

- [Yorda](https://github.com/yordadev)
- [All Contributors](../../contributors)

