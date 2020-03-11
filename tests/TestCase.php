<?php


namespace Mpbarlow\LaravelQueueDebouncer\Tests;


use Mpbarlow\LaravelQueueDebouncer\Facade\Debouncer;
use Mpbarlow\LaravelQueueDebouncer\ServiceProvider;
use Mpbarlow\LaravelQueueDebouncer\Support\CacheKeyProvider;
use Mpbarlow\LaravelQueueDebouncer\Support\UniqueIdentifierProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Debouncer' => Debouncer::class
        ];
    }
}

class CustomCacheKeyProvider extends CacheKeyProvider {}
class CustomUniqueIdentifierProvider extends UniqueIdentifierProvider {}
