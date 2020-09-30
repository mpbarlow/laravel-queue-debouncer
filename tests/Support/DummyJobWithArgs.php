<?php


namespace Mpbarlow\LaravelQueueDebouncer\Tests\Support;


use Illuminate\Foundation\Bus\Dispatchable;

class DummyJobWithArgs
{
    use Dispatchable;

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