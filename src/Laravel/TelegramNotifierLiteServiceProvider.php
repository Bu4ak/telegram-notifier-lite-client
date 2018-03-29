<?php

namespace Bu4ak\TelegramNotifierLite;

use Illuminate\Support\ServiceProvider;

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
            ], 'config'
        );
    }

    public function register()
    {
        $this->app->singleton(TelegramNotifierLite::class, function ($app) {
            return new TelegramNotifierLite(config('notifier_lite.token.default'));
        });
    }
}
