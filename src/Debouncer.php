<?php


namespace Mpbarlow\LaravelQueueDebouncer;


use Closure;
use DateInterval;
use DateTimeInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Facades\Cache;
use Mpbarlow\LaravelQueueDebouncer\Contracts\CacheKeyProvider;
use Mpbarlow\LaravelQueueDebouncer\Contracts\UniqueIdentifierProvider;

use function dispatch;

class Debouncer
{
    use Dispatchable, Queueable;

    protected $keyProvider;
    protected $idProvider;
    protected $factory;

    public function __construct(
        CacheKeyProvider $keyProvider,
        UniqueIdentifierProvider $idProvider,
        DispatcherFactory $factory
    ) {
        $this->keyProvider = $keyProvider;
        $this->idProvider = $idProvider;
        $this->factory = $factory;
    }

    /**
     * @param Dispatchable|Closure $job
     * @param DateTimeInterface|DateInterval|int|null $wait
     * @return PendingDispatch
     */
    public function __invoke($job, $wait)
    {
        Cache::put(
            $key = $this->keyProvider->getKey($job),
            $identifier = $this->idProvider->getIdentifier()
        );

        return dispatch(
            $this->factory->makeDispatcher($job, $key, $identifier)
        )->delay($wait);
    }

    /**
     * @param Dispatchable|Closure $job
     * @param DateTimeInterface|DateInterval|int|null $wait
     * @return PendingDispatch
     */
    public function debounce($job, $wait)
    {
        return $this($job, $wait);
    }
}
