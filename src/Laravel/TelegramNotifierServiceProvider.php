<?php

namespace Bu4ak\TelegramNotifier\Laravel;

use Bu4ak\TelegramNotifier\TelegramNotifier;
use Bu4ak\TelegramNotifier\TelegramNotifierInterface;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

/**
 *
 */
class TelegramNotifierServiceProvider extends ServiceProvider
{
    /**
     * publish config.
     */
    public function boot()
    {
        $this->publishes(
            [
                __DIR__ . '/config/notifier_bot.php' => config_path('notifier_bot.php'),
            ],
            'config'
        );
    }

    public function register()
    {
        $this->app->singleton(
            TelegramNotifierInterface::class,
            static function () {
                $httpClient = new Client(['timeout' => 10]);
                $logger = $this->app->make(LoggerInterface::class);
                return new TelegramNotifier(
                    $httpClient,
                    config('notifier_bot.api_endpoint'),
                    config('notifier_bot.token.default'),
                    $logger
                );
            }
        );
    }
}
