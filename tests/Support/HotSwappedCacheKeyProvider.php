<?php


namespace Mpbarlow\LaravelQueueDebouncer\Tests\Support;


use Mpbarlow\LaravelQueueDebouncer\Contracts\CacheKeyProvider;

class HotSwappedCacheKeyProvider implements CacheKeyProvider
{
    public const KEY = '__KEY__';

    public function getKey($job): string
    {
        return self::KEY;
    }
}