<?php


namespace Mpbarlow\LaravelQueueDebouncer\Tests;


use Mpbarlow\LaravelQueueDebouncer\Debouncer;

use PHPUnit\Framework\Attributes\Test;
use function debounce;

class GlobalFunctionTest extends TestCase
{
    #[Test]
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
