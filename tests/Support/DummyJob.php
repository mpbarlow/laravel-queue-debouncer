<?php


namespace Mpbarlow\LaravelQueueDebouncer\Tests\Support;


use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;

class DummyJob
{
    use Dispatchable, Queueable;

    public function handle()
    {
        //
    }
}