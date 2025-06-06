<?php

namespace Wontonee\Stripe\Providers;

use Illuminate\Support\ServiceProvider;

class StripeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'stripe');
        
        // Publish assets
        $this->publishes([
            __DIR__ . '/../Resources/assets' => public_path('vendor/wontonee/stripe'),
        ], 'stripe-assets');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }    /**
     * Register package config.
     *
     * @return void
     */    protected function registerConfig()
    {        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/paymentmethods.php',
            'payment_methods'
        );
        
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php',
            'core'
        );        // Load license configuration  
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/license.php',
            'stripe.license'
        );
    }
}
