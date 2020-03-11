<?php


namespace Mpbarlow\LaravelQueueDebouncer\Tests;


use Mpbarlow\LaravelQueueDebouncer\Debouncer;

use function debounce;

class GlobalFunctionTest extends TestCase
{
    /** @test */
    public function it_passes_calls_to_the_container()
    {
        $this->mock(Debouncer::class, function ($mock) {
            $mock
                ->shouldReceive('__invoke')
                ->with(null, null)
                ->once();
        });

        debounce(null, null);
    }
}
