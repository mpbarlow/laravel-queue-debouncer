<?php


namespace Mpbarlow\LaravelQueueDebouncer\Tests;


use Mpbarlow\LaravelQueueDebouncer\Debouncer;
use Mpbarlow\LaravelQueueDebouncer\Tests\Support\DummyJobWithArgsAndTrait;
use Illuminate\Support\Facades\Bus;

use function debounce;

class DebounceableTest extends TestCase
{
    /** @test */
    public function it_uses_the_new_trait_to_dispatch()
    {
        Bus::fake();

        $this->mock(Debouncer::class, function ($mock) {
            $mock
                ->shouldReceive('debounce')
                ->once();
        });

        DummyJobWithArgsAndTrait::debounce('test', now()->addMinutes(1));
    }
}
