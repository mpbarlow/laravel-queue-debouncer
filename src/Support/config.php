<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cache prefix
    |--------------------------------------------------------------------------
    |
    | To keep track of whether a given instance of a job is the most recent
    | one dispatched, a unique identifier is written to the cache. To avoid
    | clashes, this string is prefixed to the generated keys.
    |
    */
    'cache_prefix'               => 'queue_debouncer',

    /*
    |--------------------------------------------------------------------------
    | Key provider
    |--------------------------------------------------------------------------
    |
    | This class is responsible for generating the keys the unique identifiers
    | are stored against. The default implementation will use the class name
    | for class-based jobs and by-reference hash for closures. You can provide
    | your own class to customise this behaviour.
    |
    | Custom classes must implement
    | Mpbarlow\LaravelQueueDebouncer\Contracts\CacheKeyProvider
    |
    */
    'cache_key_provider'         => Mpbarlow\LaravelQueueDebouncer\Support\CacheKeyProvider::class,

    /*
    |--------------------------------------------------------------------------
    | Identifier provider
    |--------------------------------------------------------------------------
    |
    | This class is responsible for generating a unique identifier for each
    | individual dispatch of a job. The default implementation returns a
    | UUID v4. You can provide your own class to customise this behaviour.
    |
    | Custom classes must implement
    | Mpbarlow\LaravelQueueDebouncer\Contracts\UniqueIdentifierProvider
    |
    */
    'unique_identifier_provider' => Mpbarlow\LaravelQueueDebouncer\Support\UniqueIdentifierProvider::class
];
