<?php


namespace Mpbarlow\LaravelQueueDebouncer\Tests;


use Mpbarlow\LaravelQueueDebouncer\Facade\Debouncer;
use Mpbarlow\LaravelQueueDebouncer\ServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Debouncer' => Debouncer::class
        ];
    }
}
