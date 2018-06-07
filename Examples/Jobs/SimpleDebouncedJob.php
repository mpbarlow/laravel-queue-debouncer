<?php

use Mpbarlow\LaravelQueueDebouncer\DebouncedJob;

class SimpleDebouncedJob extends DebouncedJob
{
    // We do not need to declare a constructor here as the superclass handles it for us

    /**
     * Check whether we should run and if so, handle as normal.
     *
     * @return void
     */
    public function debounced()
    {
        echo "Hello, I am debounced. I have no data though :(\n";
    }
}
