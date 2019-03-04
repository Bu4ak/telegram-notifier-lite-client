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
            ],
            'config'
        );
    }

    public function register()
    {
        $this->app->singleton(TelegramNotifier::class, function () {
            return new TelegramNotifierLite(config('notifier_lite.api_base_url'), config('notifier_lite.token.default'));
        });
    }
}
