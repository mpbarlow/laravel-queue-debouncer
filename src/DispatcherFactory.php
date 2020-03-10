<?php


namespace Mpbarlow\LaravelQueueDebouncer;


use Closure;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;

use function dispatch;

class DispatcherFactory
{
    /**
     * By using a closure for the "job runner" that we push the the queue, we don't have to worry about whether the job
     * we're running is a class or a closure. If we used a class for this, we would have to handle checking whether the
     * job is a closure, and serialising it if it is.
     *
     * @param Dispatchable|Closure|string $job
     * @param string $key
     * @param string $identifier
     * @return Closure
     */
    public function makeDispatcher($job, $key, $identifier): Closure
    {
        return function () use ($job, $key, $identifier) {
            if (Cache::get($key) == $identifier) {
                Cache::forget($key);

                dispatch($job);
            }
        };
    }
}
