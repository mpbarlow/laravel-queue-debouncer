<?php

namespace Mpbarlow\LaravelQueueDebouncer;


use Mpbarlow\LaravelQueueDebouncer\Traits\InteractsWithDebouncer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

abstract class DebouncedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, InteractsWithDebouncer;

    /** @var mixed */
    protected $uniqueId;

    public function __construct($uniqueId)
    {
        $this->uniqueId = $uniqueId;
    }

    /**
     * Return the unique ID we were passed in the constructor.
     *
     * @return mixed
     */
    public function getUniqueIdentifier()
    {
        return $this->uniqueId;
    }
}