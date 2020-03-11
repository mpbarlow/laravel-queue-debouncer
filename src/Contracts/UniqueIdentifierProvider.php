<?php


namespace Mpbarlow\LaravelQueueDebouncer\Contracts;


interface UniqueIdentifierProvider
{
    public function getIdentifier(): string;
}
