<?php


namespace Mpbarlow\LaravelQueueDebouncer\Support;


use Closure;
use Mpbarlow\LaravelQueueDebouncer\Contracts\CacheKeyProvider as CacheKeyProviderContract;

use function config;

class ClosureCacheKeyProvider implements CacheKeyProviderContract
{
    protected $provider;

    public function __construct(Closure $provider)
    {
        $this->provider = $provider;
    }

    public function getKey($job): string
    {
        return config('queue_debouncer.cache_prefix') . ':' . ($this->provider)($job);
    }
}