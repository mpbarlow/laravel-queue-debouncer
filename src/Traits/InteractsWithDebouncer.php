<?php

namespace Mpbarlow\LaravelQueueDebouncer\Traits;

use Illuminate\Support\Facades\Cache;

trait InteractsWithDebouncer
{
    use ProvidesCacheKey;

    /**
     * Return the unique ID that was passed to the job.
     *
     * @return mixed
     */
    abstract function getUniqueIdentifier();

    /**
     * Called when the job is successfully debounced and should run.
     *
     * @return mixed
     */
    abstract function debounced();

    /**
     * Determine whether or not this is the instance of the job that should be allowed to run.
     *
     * @return bool
     */
    protected function shouldRun()
    {
        return Cache::get($this->cacheKey(get_class($this))) === $this->getUniqueIdentifier();
    }

    /**
     * Execute the job if it should run.
     *
     * @return mixed|null
     */
    public function handle()
    {
        if (!$this->shouldRun()) {
            $this->delete();
            return null;
        }

        return $this->debounced();
    }
}