<?php


namespace Mpbarlow\LaravelQueueDebouncer\Facade;


use Closure;
use DateInterval;
use DateTimeInterface;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Mpbarlow\LaravelQueueDebouncer\Debouncer
 * @method static PendingDispatch debounce(Dispatchable|Closure $job, DateTimeInterface|DateInterval|int|null $wait)
 */
class Debouncer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Mpbarlow\LaravelQueueDebouncer\Debouncer::class;
    }
}
