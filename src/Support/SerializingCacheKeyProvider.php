<?php


namespace Mpbarlow\LaravelQueueDebouncer\Support;


use Closure;
use Laravel\SerializableClosure\SerializableClosure;
use Mpbarlow\LaravelQueueDebouncer\Contracts\CacheKeyProvider as CacheKeyProviderContract;

use function config;
use function sha1;

class SerializingCacheKeyProvider implements CacheKeyProviderContract
{
    public function getKey($job): string
    {
        return config('queue_debouncer.cache_prefix').':'.
            sha1(serialize($job instanceof Closure ? new SerializableClosure($job) : $job));
    }
}