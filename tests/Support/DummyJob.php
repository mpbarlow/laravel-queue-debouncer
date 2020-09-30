<?php


namespace Mpbarlow\LaravelQueueDebouncer\Tests\Support;


use Illuminate\Foundation\Bus\Dispatchable;

class DummyJob
{
    use Dispatchable;

    public function handle()
    {
        //
    }
}