<?php

namespace Mpbarlow\LaravelQueueDebouncer\Traits;

use Mpbarlow\LaravelQueueDebouncer\Facade\Debouncer;

trait Debounceable
{
    /**
     * Dispatch the job with the given arguments.
     *
     * @return \Illuminate\Foundation\Bus\PendingDispatch
     */
    public static function debounce(...$jobProperties)
    {
        $wait = array_pop($jobProperties);

        return Debouncer::debounce(new static(...$jobProperties), $wait);
    }
}
