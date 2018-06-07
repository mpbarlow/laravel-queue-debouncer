<?php

namespace Mpbarlow\LaravelQueueDebouncer\Interfaces;

interface DebouncesJobs
{
    /**
     * Return a unique identifier for the job to be debounced.
     *
     * @return mixed
     */
    function generateUniqueId();

    /**
     * Store the job identifier in the cache.
     *
     * @param string $uniqueId
     * @return void
     */
    function cacheJob(string $uniqueId);
}