<?php


namespace Mpbarlow\LaravelQueueDebouncer\Facade;


use Illuminate\Support\Facades\Facade;

class Debouncer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Mpbarlow\LaravelQueueDebouncer\Debouncer::class;
    }
}
