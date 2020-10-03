<?php


namespace Mpbarlow\LaravelQueueDebouncer\Facade;


use Illuminate\Support\Facades\Facade;

/**
 * @see \Mpbarlow\LaravelQueueDebouncer\Debouncer
 * @method static \Illuminate\Foundation\Bus\PendingDispatch debounce(\Illuminate\Foundation\Bus\Dispatchable|\Illuminate\Foundation\Bus\PendingChain|\Closure $job, \DateTimeInterface|\DateInterval|int|null $wait)
 */
class Debouncer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Mpbarlow\LaravelQueueDebouncer\Debouncer::class;
    }
}
