<?php


namespace Mpbarlow\LaravelQueueDebouncer\Tests;


use Illuminate\Foundation\Bus\Dispatchable;
use Mpbarlow\LaravelQueueDebouncer\Support\CacheKeyProvider;
use ReflectionFunction;

use function sha1;
use function strlen;
use function substr;

class CacheKeyProviderTest extends TestCase
{
    /** @test */
    public function it_applies_the_cache_prefix()
    {
        $this->app['config']->set('queue_debouncer.cache_prefix', $prefix = '__PREFIX__');

        $provider = new CacheKeyProvider();

        $closureJob = function () {};

        $this->assertEquals($prefix . ':' . DummyJob::class, $provider->getKey(new DummyJob()));

        $this->assertEquals(
            $prefix . ':' . sha1((string)(new ReflectionFunction($closureJob))),
            $provider->getKey($closureJob)
        );
    }

    /** @test */
    public function it_uses_the_class_name_for_job_classes()
    {
        $this->app['config']->set('queue_debouncer.cache_prefix', $prefix = '__PREFIX__');

        $provider = new CacheKeyProvider();

        // Assert the FQ classname is appended to the cache prefix
        $this->assertEquals(
            DummyJob::class,
            substr($provider->getKey(new DummyJob()), strlen($prefix) + 1)
        );
    }

    /** @test */
    public function it_generates_a_unique_key_for_closures()
    {
        $provider = new CacheKeyProvider();

        $job1 = function () {};
        $job2 = function () {};

        $key1 = $provider->getKey($job1);
        $key2 = $provider->getKey($job2);

        // Assert different closures result in different keys.
        $this->assertNotEquals($key1, $key2);
    }

    /** @test */
    public function it_generates_a_consistent_key_for_closures()
    {
        $provider = new CacheKeyProvider();

        $job = function () {};

        $key1 = $provider->getKey($job);
        $key2 = $provider->getKey($job);

        // Assert the same closure results in the same key each time.
        $this->assertEquals($key1, $key2);
    }
}

class DummyJob {
    use Dispatchable;
}
