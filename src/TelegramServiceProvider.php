<?php

namespace Nekkoy\GatewayTelegram;

use Illuminate\Support\ServiceProvider;

/**
 *
 */
class TelegramServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(\Nekkoy\GatewayTelegram\Services\GatewayService::class, function ($app) {
            return new \Nekkoy\GatewayTelegram\Services\GatewayService();
        });

        $this->app->singleton('gateway-telegram', function ($app) {
            return new \Nekkoy\GatewayTelegram\Services\GatewayTelegramService();
        });
    }

    public function boot()
    {
        $this->publishes([__DIR__ . '/../config/config.php' => config_path('gateway-telegram.php')], 'config');
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'gateway-telegram');
    }
}
