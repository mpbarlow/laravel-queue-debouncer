<?php


namespace Mpbarlow\LaravelQueueDebouncer\Contracts;


use Closure;
use Illuminate\Foundation\Bus\Dispatchable;

interface CacheKeyProvider
{
    /**
     * @param Dispatchable|Closure $job
     * @return string
     */
    public function getKey($job): string;
}
