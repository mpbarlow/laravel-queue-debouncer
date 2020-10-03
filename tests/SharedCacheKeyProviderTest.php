<?php


namespace Mpbarlow\LaravelQueueDebouncer\Tests;


use Mpbarlow\LaravelQueueDebouncer\Support\CacheKeyProvider;
use Mpbarlow\LaravelQueueDebouncer\Support\SerializingCacheKeyProvider;
use Mpbarlow\LaravelQueueDebouncer\Tests\Support\DummyJob;

use function strpos;

class SharedCacheKeyProviderTest extends TestCase
{
    /**
     * @test
     * @dataProvider cacheKeyProviderProvider
     */
    public function it_applies_the_cache_prefix_to_classes($provider)
    {
        $this->app['config']->set('queue_debouncer.cache_prefix', $prefix = '__PREFIX__');

        $job = new DummyJob();

        $this->assertSame(
            0,
            strpos($provider->getKey($job), $prefix)
        );
    }

    /**
     * @test
     * @dataProvider cacheKeyProviderProvider
     */
    public function it_applies_the_cache_prefix_to_chains($provider)
    {
        $this->app['config']->set('queue_debouncer.cache_prefix', $prefix = '__PREFIX__');

        $job = DummyJob::withChain([new DummyJob()]);

        $this->assertSame(
            0,
            strpos($provider->getKey($job), $prefix)
        );
    }

    /**
     * @test
     * @dataProvider cacheKeyProviderProvider
     */
    public function it_applies_the_cache_prefix_to_closures($provider)
    {
        $this->app['config']->set('queue_debouncer.cache_prefix', $prefix = '__PREFIX__');

        $job = function () {
        };

        $this->assertSame(
            0,
            strpos($provider->getKey($job), $prefix)
        );
    }

    /**
     * @test
     * @dataProvider cacheKeyProviderProvider
     */
    public function it_generates_a_unique_key_for_closures($provider)
    {
        $job1 = function () {
        };
        $job2 = function () {
        };

        $key1 = $provider->getKey($job1);
        $key2 = $provider->getKey($job2);

        // Assert different closures result in different keys.
        $this->assertNotEquals($key1, $key2);
    }

    /**
     * @test
     * @dataProvider cacheKeyProviderProvider
     */
    public function it_generates_a_consistent_key_for_closures($provider)
    {
        $job = function () {
        };

        $key1 = $provider->getKey($job);
        $key2 = $provider->getKey($job);

        // Assert the same closure results in the same key each time.
        $this->assertEquals($key1, $key2);
    }

    public function cacheKeyProviderProvider()
    {
        return [
            [new CacheKeyProvider()],
            [new SerializingCacheKeyProvider()]
        ];
    }
}