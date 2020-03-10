<?php


namespace Mpbarlow\LaravelQueueDebouncer\Support;


use Closure;
use Mpbarlow\LaravelQueueDebouncer\Contracts\CacheKeyProvider as CacheKeyProviderContract;
use ReflectionFunction;

use function config;
use function get_class;
use function sha1;

class CacheKeyProvider implements CacheKeyProviderContract
{
    public function getKey($job): string
    {
        if ($job instanceof Closure) {
            $identifier = sha1((string)(new ReflectionFunction($job)));
        } else {
            $identifier = get_class($job);
        }

        return config('queue_debouncer.cache_prefix') . ':' . $identifier;
    }
}
