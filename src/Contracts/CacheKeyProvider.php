<?php


namespace Mpbarlow\LaravelQueueDebouncer\Contracts;


use Closure;

interface CacheKeyProvider
{
    /**
     * @param \Illuminate\Foundation\Bus\Dispatchable|\Illuminate\Foundation\Bus\PendingChain|Closure $job
     * @return string
     */
    public function getKey($job): string;
}
