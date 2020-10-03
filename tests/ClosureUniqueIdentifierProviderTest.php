<?php


namespace Mpbarlow\LaravelQueueDebouncer\Tests;


use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Mpbarlow\LaravelQueueDebouncer\Debouncer;

use const PHP_INT_MAX;

class ClosureUniqueIdentifierProviderTest extends TestCase
{
    /** @test */
    public function it_calls_the_provided_closure()
    {
        Bus::fake();

        $prefix = '__PREFIX__';
        $this->app['config']->set('queue_debouncer.cache_prefix', $prefix);

        $key = '__KEY__';

        $closure = function () use ($key) {
            return $key;
        };

        $debouncer = $this->app->make(Debouncer::class)
            ->usingCacheKeyProvider($closure)
            ->usingUniqueIdentifierProvider($closure);

        $debouncer->debounce(function () {}, PHP_INT_MAX);

        $this->assertSame(
            $key,
            Cache::get("{$prefix}:{$key}")
        );
    }
}