<?php

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Bus\PendingDispatch;
use Mpbarlow\LaravelQueueDebouncer\Debouncer;

if (! function_exists('debounce')) {
    /**
     * @param Dispatchable|Closure $job
     * @param DateTimeInterface|DateInterval|int|null $wait
     * @return PendingDispatch
     */
    function debounce($job, $wait)
    {
        return app(Debouncer::class)($job, $wait);
    }
}
