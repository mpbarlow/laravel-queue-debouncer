<?php


namespace Mpbarlow\LaravelQueueDebouncer;


use Closure;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
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

    public function usingCacheKeyProvider(CacheKeyProvider $provider): self
    {
        $this->keyProvider = $provider;

        return $this;
    }

    public function usingUniqueIdentifierProvider(UniqueIdentifierProvider $provider): self
    {
        $this->idProvider = $provider;

        return $this;
    }

    /**
     * @param Dispatchable|\Illuminate\Foundation\Bus\PendingChain|\Illuminate\Bus\PendingBatch|Closure $job
     * @param \DateTimeInterface|\DateInterval|int|null $wait
     * @return \Illuminate\Foundation\Bus\PendingDispatch
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
     * @param Dispatchable|\Illuminate\Foundation\Bus\PendingChain|\Illuminate\Bus\PendingBatch|Closure $job
     * @param \DateTimeInterface|\DateInterval|int|null $wait
     * @return \Illuminate\Foundation\Bus\PendingDispatch
     */
    public function debounce($job, $wait)
    {
        return $this($job, $wait);
    }
}
