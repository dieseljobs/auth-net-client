<?php

namespace TheLHC\AuthNetClient;

use Illuminate\Support\ServiceProvider;

class AuthNetClientServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/auth_net_client.php' => config_path('auth_net_client.php')
        ], 'config');
        $this->loadViewsFrom(__DIR__.'/templates', 'auth-net-client');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/auth_net_client.php', 'auth_net_client');
    }

}
