<?php

use Mpbarlow\LaravelQueueDebouncer\Debounce;

include_once './Jobs/SimpleDebouncedJob.php';
include_once './Jobs/DebouncedJobWithExtraArguments.php';

class DispatchDebouncedJobs
{
    public function dispatch()
    {
        // If the job does not need any parameters, the class name and wait time is sufficient.
        dispatch(new Debounce(SimpleDebouncedJob::class, 10));
        sleep(1);
        dispatch(new Debounce(SimpleDebouncedJob::class, 10));
        sleep(1);
        dispatch(new Debounce(SimpleDebouncedJob::class, 10));
        sleep(1);

        // Any additional parameters that are needed can be appended to the end. They are passed straight
        // to the debounced job's constructor.
        dispatch(new Debounce(DebouncedJobWithExtraArguments::class, 10, 'some', 'extra', 'arguments'));
        sleep(1);
        dispatch(new Debounce(DebouncedJobWithExtraArguments::class, 10, 'some', 'extra', 'arguments'));
        sleep(1);
        dispatch(new Debounce(DebouncedJobWithExtraArguments::class, 10, 'some', 'extra', 'arguments'));
        sleep(1);

        // You should only see the output of each job appear once when monitoring your queue.
    }
}