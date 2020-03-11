<?php


namespace Mpbarlow\LaravelQueueDebouncer\Tests;


use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\CallQueuedClosure;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Mpbarlow\LaravelQueueDebouncer\Debouncer;
use Mpbarlow\LaravelQueueDebouncer\DispatcherFactory;
use Mpbarlow\LaravelQueueDebouncer\Support\CacheKeyProvider;
use Mpbarlow\LaravelQueueDebouncer\Support\UniqueIdentifierProvider;
use ReflectionFunction;


class DebounceTest extends TestCase
{
    /** @test */
    public function it_injects_the_configured_cache_key_provider_and_unique_identifier_provider()
    {
        Bus::fake();

        $this->app['config']->set('queue_debouncer.cache_key_provider', CustomCacheKeyProvider::class);
        $this->app['config']->set('queue_debouncer.unique_identifier_provider', CustomUniqueIdentifierProvider::class);

        $this->mock(CustomCacheKeyProvider::class, function ($mock) {
            $mock
                ->shouldReceive('getKey')
                ->once();
        });

        $this->mock(CustomUniqueIdentifierProvider::class, function ($mock) {
            $mock
                ->shouldReceive('getIdentifier')
                ->once();
        });

        $this->app->make(Debouncer::class)(null, null);
    }

    /**
     * @test
     * @dataProvider jobProvider
     */
    public function it_debounces_jobs($job, $expectedDispatch)
    {
        // So this is pretty tricky to test. It's a job that dispatches another job, so when we're faking the bus we
        // have to get a bit creative.
        Bus::fake();

        // First we mock the ID provider so we know what identifiers we'll be getting.
        $this->mock(UniqueIdentifierProvider::class, function ($mock) {
            $mock
                ->shouldReceive('getIdentifier')
                ->andReturn(1, 2, 3);
        });

        $key = $this->app->make(CacheKeyProvider::class)->getKey($job);
        $debouncer = $this->app->make(Debouncer::class);

        // Then we call the debouncer three times, asserting that each time, it's setting the cache value correctly,
        // and dispatching a runner closure with the values we expect.
        foreach ([1, 2, 3] as $i) {
            $debouncer($job, PHP_INT_MAX);
            $this->assertEquals($i, Cache::get($key));

            Bus::assertDispatched(CallQueuedClosure::class, function ($dispatch) use ($job, $key, $i) {
                // Get the variables captured by our dispatcher closure; i.e. the job, key, and identifier.
                $captured = (new ReflectionFunction($dispatch->closure->getClosure()))->getStaticVariables();

                return $dispatch->delay === PHP_INT_MAX
                    && $captured['key'] === $key
                    && $captured['identifier'] == $i
                    && $captured['job'] instanceof $job;
            });
        }

        Bus::assertDispatched(CallQueuedClosure::class, 3);

        // Reset the bus so we can tell the difference between a closure job and the runner closures.
        Bus::fake();

        $factory = $this->app->make(DispatcherFactory::class);

        // Next, we build and dispatch runners with the values we just encountered above. With these, we can assert
        // that only the one matching the identifier in the cache (i.e. the final one) actually dispatches our job.
        $factory->makeDispatcher($job, $key, 1)();
        Bus::assertNotDispatched($expectedDispatch);

        $factory->makeDispatcher($job, $key, 2)();
        Bus::assertNotDispatched($expectedDispatch);

        $factory->makeDispatcher($job, $key, 3)();
        Bus::assertDispatched($expectedDispatch, 1);

        // Finally, check that the runner cleared the cache when it dispatched the job.
        $this->assertNull(Cache::get($key));
    }

    public function jobProvider(): array
    {
        return [
            [new Job(), Job::class],
            [function () {}, CallQueuedClosure::class]
        ];
    }
}

class Job
{
    use Dispatchable;

    public function handle()
    {
        //
    }
}
