<?php


namespace Mpbarlow\LaravelQueueDebouncer\Contracts;


use Illuminate\Foundation\Bus\Dispatchable;

interface CacheKeyProvider
{
    /**
     * @param Dispatchable|string $job
     * @return string
     */
    public function getKey($job): string;
}
