<?php

use Mpbarlow\LaravelQueueDebouncer\DebouncedJob;

class DebouncedJobWithExtraArguments extends DebouncedJob
{
    /** @var mixed */
    protected $some;
    /** @var mixed */
    protected $extra;
    /** @var mixed */
    protected $arguments;

    public function __construct($uniqueId, $some, $extra, $arguments)
    {
        // We must make sure to provide the superclass constructor with the unique ID
        parent::__construct($uniqueId);

        $this->some = $some;
        $this->extra = $extra;
        $this->arguments = $arguments;
    }

    /**
     * Check whether we should run and if so, handle as normal.
     *
     * @return void
     */
    public function debounced()
    {
        echo "Hello, I am debounced. I have {$this->some} {$this->extra} {$this->arguments} though! :D\n";
    }
}