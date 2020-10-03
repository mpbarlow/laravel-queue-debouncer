<?php


namespace Mpbarlow\LaravelQueueDebouncer\Tests;


use Mpbarlow\LaravelQueueDebouncer\Contracts\CacheKeyProvider as CacheKeyProviderContract;
use Mpbarlow\LaravelQueueDebouncer\Contracts\UniqueIdentifierProvider as UniqueIdentifierProviderContract;
use Mpbarlow\LaravelQueueDebouncer\Tests\Support\CustomCacheKeyProvider;
use Mpbarlow\LaravelQueueDebouncer\Tests\Support\CustomUniqueIdentifierProvider;

class ServiceProviderTest extends TestCase
{
    /** @test */
    public function it_fetches_the_cache_key_provider_from_the_config()
    {
        $this->app['config']->set('queue_debouncer.cache_key_provider', CustomCacheKeyProvider::class);

        $this->assertInstanceOf(
            CustomCacheKeyProvider::class,
            $this->app->make(CacheKeyProviderContract::class)
        );
    }

    /** @test */
    public function it_fetches_the_unique_identifier_provider_from_the_config()
    {
        $this->app['config']->set('queue_debouncer.unique_identifier_provider', CustomUniqueIdentifierProvider::class);

        $this->assertInstanceOf(
            CustomUniqueIdentifierProvider::class,
            $this->app->make(UniqueIdentifierProviderContract::class)
        );
    }
}
