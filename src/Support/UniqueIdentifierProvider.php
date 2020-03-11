<?php


namespace Mpbarlow\LaravelQueueDebouncer\Support;


use Mpbarlow\LaravelQueueDebouncer\Contracts\UniqueIdentifierProvider as UniqueIdentifierProviderContract;
use Ramsey\Uuid\Uuid;

class UniqueIdentifierProvider implements UniqueIdentifierProviderContract
{
    public function getIdentifier(): string
    {
        return Uuid::uuid4()->toString();
    }
}
