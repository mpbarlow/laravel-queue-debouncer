<?php


namespace Mpbarlow\LaravelQueueDebouncer\Tests\Support;


use Mpbarlow\LaravelQueueDebouncer\Contracts\UniqueIdentifierProvider;

class HotSwappedIdentifierProvider implements UniqueIdentifierProvider
{
    public const IDENTIFIER = '__IDENTIFIER__';

    public function getIdentifier(): string
    {
        return self::IDENTIFIER;
    }
}