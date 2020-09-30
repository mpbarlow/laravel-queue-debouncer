<?php


namespace Mpbarlow\LaravelQueueDebouncer\Contracts;


use Closure;

interface CacheKeyProvider
{
    /**
     * @param \Illuminate\Foundation\Bus\Dispatchable|\Illuminate\Foundation\Bus\PendingChain|\Illuminate\Bus\PendingBatch|Closure $job
     * @return string
     */
    public function getKey($job): string;
}
