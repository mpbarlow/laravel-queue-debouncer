<?php

namespace Mpbarlow\LaravelQueueDebouncer;

use Mpbarlow\LaravelQueueDebouncer\Interfaces\DebouncesJobs;
use Mpbarlow\LaravelQueueDebouncer\Traits\ProvidesCacheKey;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Ramsey\Uuid\Uuid;

class Debounce implements ShouldQueue, DebouncesJobs
{
    use Dispatchable, Queueable, ProvidesCacheKey;

    /** @var string */
    protected $debounceable;
    /** @var int */
    protected $waitTime;
    /** @var array */
    protected $jobArgs;

    /**
     * Create a new job instance.
     *
     * @param string $debounceable  The FQ class name of the job to debounce
     * @param int $waitTime         The time in seconds to debounce for
     * @param array $jobArgs        Any arguments to pass through to the job
     * @return void
     */
    public function __construct(string $debounceable, int $waitTime, ...$jobArgs)
    {
        $this->debounceable = $debounceable;
        $this->waitTime = $waitTime;
        $this->jobArgs = $jobArgs ?? [];
    }

    /**
     * Create a unique ID for the job, store it in the cache, then instantiate and dispatch the job with the unique ID.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function handle()
    {
        // Serialize in case generateUniqueId() has been overridden and is non-scalar.
        $uniqueId = serialize($this->generateUniqueId());

        $this->cacheJob($uniqueId);

        (new \ReflectionMethod($this->debounceable, 'dispatch'))
            ->invoke(null, $uniqueId, ...$this->jobArgs)
            ->delay(Carbon::now()->addSeconds($this->waitTime));
    }

    public function generateUniqueId()
    {
        return Uuid::uuid4()->toString();
    }

    public function cacheJob(string $uniqueId)
    {
        Cache::put($this->cacheKey($this->debounceable), $uniqueId, max(1, (int) (($this->waitTime * 2) / 60)));
    }
}
