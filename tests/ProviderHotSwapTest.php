<?php


namespace Mpbarlow\LaravelQueueDebouncer\Tests;


use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Mpbarlow\LaravelQueueDebouncer\Debouncer;
use Mpbarlow\LaravelQueueDebouncer\Tests\Support\HotSwappedCacheKeyProvider;
use Mpbarlow\LaravelQueueDebouncer\Tests\Support\HotSwappedIdentifierProvider;
use PHPUnit\Framework\Attributes\Test;

class ProviderHotSwapTest extends TestCase
{
    #[Test]
    public function the_cache_key_provider_can_be_hot_swapped()
    {
        Bus::fake();

        $debouncer = $this->app->make(Debouncer::class);

        $this->assertFalse(
            Cache::has(HotSwappedCacheKeyProvider::KEY)
        );

        $debouncer
            ->usingCacheKeyProvider(new HotSwappedCacheKeyProvider())
            ->debounce(function () {}, 0);

        $this->assertTrue(
            Cache::has(HotSwappedCacheKeyProvider::KEY)
        );
    }

    #[Test]
    public function the_identifier_provider_can_be_hot_swapped()
    {
        Bus::fake();

        $debouncer = $this->app->make(Debouncer::class);

        $this->assertFalse(
            Cache::has(HotSwappedCacheKeyProvider::KEY)
        );

        $debouncer
            ->usingCacheKeyProvider(new HotSwappedCacheKeyProvider())
            ->usingUniqueIdentifierProvider(new HotSwappedIdentifierProvider())
            ->debounce(function () {}, 0);

        $this->assertSame(
            HotSwappedIdentifierProvider::IDENTIFIER,
            Cache::get(HotSwappedCacheKeyProvider::KEY)
        );
    }
}