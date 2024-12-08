<?php


namespace Mpbarlow\LaravelQueueDebouncer\Tests;


use Mpbarlow\LaravelQueueDebouncer\Support\CacheKeyProvider;
use Mpbarlow\LaravelQueueDebouncer\Tests\Support\DummyJob;

use PHPUnit\Framework\Attributes\Test;
use function strlen;
use function substr;

class CacheKeyProviderTest extends TestCase
{
    #[Test]
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
}
