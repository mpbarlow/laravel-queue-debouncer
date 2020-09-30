<?php

use Mpbarlow\LaravelQueueDebouncer\Debouncer;

if (! function_exists('debounce')) {
    /**
     * @param \Illuminate\Foundation\Bus\Dispatchable|\Illuminate\Foundation\Bus\PendingChain|\Illuminate\Bus\PendingBatch|Closure $job
     * @param DateTimeInterface|DateInterval|int|null $wait
     * @return \Illuminate\Foundation\Bus\PendingDispatch
     */
    function debounce($job, $wait)
    {
        return app(Debouncer::class)($job, $wait);
    }
}
