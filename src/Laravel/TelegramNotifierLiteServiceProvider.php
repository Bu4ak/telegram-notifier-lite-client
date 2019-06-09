<?php

namespace Bu4ak\TelegramNotifierLite;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

/**
 * Class TelegramNotifierLiteServiceProvider.
 */
class TelegramNotifierLiteServiceProvider extends ServiceProvider
{
    /**
     * publish config.
     */
    public function boot()
    {
        $this->publishes(
            [
                __DIR__.'/config/notifier_lite.php' => config_path('notifier_lite.php'),
            ],
            'config'
        );
    }

    public function register()
    {
        $this->app->singleton(TelegramNotifier::class, static function () {
            $httpClient = new Client(['base_uri' => config('notifier_lite.api_base_url')]);
            $logger = $this->app->make(LoggerInterface::class);
            return new TelegramNotifierLite($httpClient, $logger, config('notifier_lite.token.default'));
        });
    }
}
