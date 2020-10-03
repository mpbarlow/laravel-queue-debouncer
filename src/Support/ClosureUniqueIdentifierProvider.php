<?php


namespace Mpbarlow\LaravelQueueDebouncer\Support;


use Closure;
use Mpbarlow\LaravelQueueDebouncer\Contracts\UniqueIdentifierProvider as UniqueIdentifierProviderContract;

class ClosureUniqueIdentifierProvider implements UniqueIdentifierProviderContract
{
    protected $provider;

    public function __construct(Closure $provider)
    {
        $this->provider = $provider;
    }

    public function getIdentifier(): string
    {
        return ($this->provider)();
    }
}