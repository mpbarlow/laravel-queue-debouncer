<?php


namespace Mpbarlow\LaravelQueueDebouncer\Tests;


use Mpbarlow\LaravelQueueDebouncer\Facade\Debouncer;
use Mpbarlow\LaravelQueueDebouncer\ServiceProvider;

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
