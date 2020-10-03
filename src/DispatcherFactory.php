<?php


namespace Mpbarlow\LaravelQueueDebouncer;


use Closure;
use Illuminate\Foundation\Bus\PendingChain;
use Illuminate\Support\Facades\Cache;

use function dispatch;

class DispatcherFactory
{
    /**
     * By using a closure for the "dispatcher" that we push the the queue, we don't have to worry about whether the
     * underlying job we're debouncing is a class or a closure, because the framework will handle it for us.
     * If we used a class as the dispatcher, we would have to check whether the job is a closure ourselves, and
     * serialise it if it was.
     *
     * @param \Illuminate\Foundation\Bus\Dispatchable|PendingChain|Closure $job
     * @param string $key
     * @param string $identifier
     * @return Closure
     */
    public function makeDispatcher($job, $key, $identifier): Closure
    {
        return function () use ($job, $key, $identifier) {
            if (Cache::get($key) == $identifier) {
                Cache::forget($key);

                if ($job instanceof PendingChain) {
                    $job->dispatch();
                } else {
                    dispatch($job);
                }
            }
        };
    }
}
