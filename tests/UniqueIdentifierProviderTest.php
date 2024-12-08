<?php


namespace Mpbarlow\LaravelQueueDebouncer\Tests;


use Mpbarlow\LaravelQueueDebouncer\Support\UniqueIdentifierProvider;

use PHPUnit\Framework\Attributes\Test;
use function array_fill;
use function array_map;
use function collect;

class UniqueIdentifierProviderTest extends TestCase
{
    #[Test]
    public function it_generates_unique_identifiers()
    {
        $provider = new UniqueIdentifierProvider();

        // I wonder how big a sample size you would need to reasonably be able say that this test actually confirms
        // randomness? I never was any good at statistics.
        $ids = array_map(function () use ($provider) {
            return $provider->getIdentifier();
        }, array_fill(0, 10, null));

        $this->assertEquals(10, collect($ids)->unique()->count());
    }
}
