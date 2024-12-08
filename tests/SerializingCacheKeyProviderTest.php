<?php


namespace Mpbarlow\LaravelQueueDebouncer\Tests;


use Mpbarlow\LaravelQueueDebouncer\Support\SerializingCacheKeyProvider;
use Mpbarlow\LaravelQueueDebouncer\Tests\Support\DummyJob;
use Mpbarlow\LaravelQueueDebouncer\Tests\Support\DummyJobWithArgs;
use PHPUnit\Framework\Attributes\Test;

class SerializingCacheKeyProviderTest extends TestCase
{
    #[Test]
    public function it_generates_the_same_key_when_objects_are_equal()
    {
        $provider = new SerializingCacheKeyProvider();

        $job1 = new DummyJobWithArgs('hello');
        $job2 = new DummyJobWithArgs('hello');

        $this->assertSame(
            $provider->getKey($job1),
            $provider->getKey($job2)
        );
    }

    #[Test]
    public function it_generates_a_unique_key_when_objects_are_not_equal()
    {
        $provider = new SerializingCacheKeyProvider();

        $job1 = new DummyJobWithArgs('hello');
        $job2 = new DummyJobWithArgs('world');

        $this->assertNotSame(
            $provider->getKey($job1),
            $provider->getKey($job2)
        );
    }

    #[Test]
    public function it_allows_for_chains_to_be_debounced()
    {
        $provider = new SerializingCacheKeyProvider();

        $chain1 = DummyJob::withChain([new DummyJobWithArgs('hello')]);
        $chain2 = DummyJob::withChain([new DummyJobWithArgs('hello')]);

        $chain3 = DummyJob::withChain([new DummyJobWithArgs('world')]);

        $this->assertSame(
            $provider->getKey($chain1),
            $provider->getKey($chain2)
        );

        $this->assertNotSame(
            $provider->getKey($chain1),
            $provider->getKey($chain3)
        );
    }
}