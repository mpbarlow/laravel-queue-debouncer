<?php


namespace Mpbarlow\LaravelQueueDebouncer\Tests\Support;


use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;

class DummyJobWithArgs
{
    use Dispatchable, Queueable;

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