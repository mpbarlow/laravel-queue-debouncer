<?php

namespace Mpbarlow\LaravelQueueDebouncer\Traits;


trait ProvidesCacheKey
{
    protected function cacheKey(string $key)
    {
        return "\Mpbarlow\LaravelQueueDebouncer:{$key}";
    }
}