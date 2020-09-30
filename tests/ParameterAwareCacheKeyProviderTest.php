<?php


namespace Mpbarlow\LaravelQueueDebouncer\Tests;


use Illuminate\Support\Facades\Bus;
use Mpbarlow\LaravelQueueDebouncer\Support\ParameterAwareCacheKeyProvider;
use Mpbarlow\LaravelQueueDebouncer\Tests\Support\DummyJob;
use Mpbarlow\LaravelQueueDebouncer\Tests\Support\DummyJobWithArgs;

use function class_exists;

class ParameterAwareCacheKeyProviderTest extends TestCase
{
    /** @test */
    public function it_generates_the_same_key_when_objects_are_equal()
    {
        $provider = new ParameterAwareCacheKeyProvider();

        $job1 = new DummyJobWithArgs('hello');
        $job2 = new DummyJobWithArgs('hello');

        $this->assertSame(
            $provider->getKey($job1),
            $provider->getKey($job2)
        );
    }

    /** @test */
    public function it_generates_a_unique_key_when_objects_are_not_equal()
    {
        $provider = new ParameterAwareCacheKeyProvider();

        $job1 = new DummyJobWithArgs('hello');
        $job2 = new DummyJobWithArgs('world');

        $this->assertNotSame(
            $provider->getKey($job1),
            $provider->getKey($job2)
        );
    }

    /** @test */
    public function it_allows_for_chains_to_be_debounced()
    {
        $provider = new ParameterAwareCacheKeyProvider();

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

    /** @test */
    public function it_allows_for_batches_to_be_debounced()
    {
        if (! class_exists('\Illuminate\Bus\PendingBatch')) {
            $this->markTestSkipped('[\Illuminate\Bus\PendingBatch] is only available on Laravel >= 8.0.');
            return;
        }

        $provider = new ParameterAwareCacheKeyProvider();

        $batch1 = Bus::batch([new DummyJobWithArgs('hello')]);
        $batch2 = Bus::batch([new DummyJobWithArgs('hello')]);

        $batch3 = Bus::batch([new DummyJobWithArgs('world')]);

        $this->assertSame(
            $provider->getKey($batch1),
            $provider->getKey($batch2)
        );

        $this->assertNotSame(
            $provider->getKey($batch1),
            $provider->getKey($batch3)
        );
    }
}