<?php


namespace Mpbarlow\LaravelQueueDebouncer\Support;


use Mpbarlow\LaravelQueueDebouncer\Contracts\CacheKeyProvider as CacheKeyProviderContract;

use function config;
use function sha1;

class SerializingCacheKeyProvider implements CacheKeyProviderContract
{
    public function getKey($job): string
    {
        return config('queue_debouncer.cache_prefix') . ':' . sha1(\Opis\Closure\serialize($job));
    }
}