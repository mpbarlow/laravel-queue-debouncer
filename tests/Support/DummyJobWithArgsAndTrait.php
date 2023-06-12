<?php


namespace Mpbarlow\LaravelQueueDebouncer\Tests\Support;


use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Mpbarlow\LaravelQueueDebouncer\Traits\Debouncable;

class DummyJobWithArgsAndTrait
{
    use Dispatchable, Queueable, Debouncable;

    protected $arg;

    public function __construct($arg)
    {
        $this->arg = $arg;
    }

    public function handle()
    {
        //
    }
}
