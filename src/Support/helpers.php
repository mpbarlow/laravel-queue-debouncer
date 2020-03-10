<?php

use Mpbarlow\LaravelQueueDebouncer\Debouncer;

if (! function_exists('debounce')) {
    function debounce($job, $wait)
    {
        return app(Debouncer::class)($job, $wait);
    }
}
