<?php


namespace Mpbarlow\LaravelQueueDebouncer;


use Closure;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use Mpbarlow\LaravelQueueDebouncer\Contracts\CacheKeyProvider;
use Mpbarlow\LaravelQueueDebouncer\Contracts\UniqueIdentifierProvider;
use Mpbarlow\LaravelQueueDebouncer\Support\ClosureCacheKeyProvider;
use Mpbarlow\LaravelQueueDebouncer\Support\ClosureUniqueIdentifierProvider;

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
     * @param CacheKeyProvider|Closure $provider
     * @return $this
     */
    public function usingCacheKeyProvider($provider): self
    {
        $this->keyProvider = $provider instanceof Closure
            ? new ClosureCacheKeyProvider($provider)
            : $provider;

        return $this;
    }

    /**
     * @param UniqueIdentifierProvider|Closure $provider
     * @return $this
     */
    public function usingUniqueIdentifierProvider($provider): self
    {
        $this->idProvider = $provider instanceof Closure
            ? new ClosureUniqueIdentifierProvider($provider)
            : $provider;

        return $this;
    }

    /**
     * @param Dispatchable|\Illuminate\Foundation\Bus\PendingChain|Closure $job
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
     * @param Dispatchable|\Illuminate\Foundation\Bus\PendingChain|Closure $job
     * @param \DateTimeInterface|\DateInterval|int|null $wait
     * @return \Illuminate\Foundation\Bus\PendingDispatch
     */
    public function debounce($job, $wait)
    {
        return $this($job, $wait);
    }
}
