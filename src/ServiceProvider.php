<?php


namespace Mpbarlow\LaravelQueueDebouncer;


use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Mpbarlow\LaravelQueueDebouncer\Contracts\CacheKeyProvider;
use Mpbarlow\LaravelQueueDebouncer\Contracts\UniqueIdentifierProvider;

use function config_path;

class ServiceProvider extends IlluminateServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Support/config.php', 'queue_debouncer');

        $this->app->bind(CacheKeyProvider::class, function (Application $app) {
            return $app->make(
                $app['config']->get('queue_debouncer.cache_key_provider')
            );
        });

        $this->app->bind(UniqueIdentifierProvider::class, function (Application $app) {
            return $app->make(
                $app['config']->get('queue_debouncer.unique_identifier_provider')
            );
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Support/config.php' => config_path('queue_debouncer.php')
        ]);
    }
}
