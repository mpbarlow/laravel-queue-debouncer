# Laravel Queue Debouncer

### Easy queue job debouncing

## Requirements

- Laravel >= 11.0
- An async queue driver

### Previous releases
* For Laravel 10.0 ..< 11.0 support, check out [v3.0.0](https://github.com/mpbarlow/laravel-queue-debouncer/releases/tag/3.0.0)
* For Laravel 6.0 ..< 10.0 support, check out [v2.6.0](https://github.com/mpbarlow/laravel-queue-debouncer/releases/tag/2.6.0)
* For Laravel 5.5 ..< 6.0 support, check out [v1.0.2](https://github.com/mpbarlow/laravel-queue-debouncer/tree/1.0.2)

## Installation

```bash
$ composer require mpbarlow/laravel-queue-debouncer
```

## Background

This package allows any queue job or chain in your Laravel application to be debounced, meaning that no matter how many times it’s dispatched within the specified timeout window, it will only run once.

For example, imagine you dispatch a job to rebuild a cache every time a record is updated, but the job is resource intensive. Debouncing the job with a five minute wait would ensure that the cache is only rebuilt once, five minutes after you finish making changes.

## Usage

Debounced jobs can largely be treated like any other dispatched job. The debouncer takes two arguments, the actual `$job` you want to run, and the `$wait` period.

As with a regular dispatch, `$job` can be either a class implementing `Illuminate\Foundation\Bus\Dispatchable`, a chain, or a closure. `$wait` accepts any argument that the `delay` method on a dispatch accepts (i.e. a `DateTimeInterface` or a number of seconds).

The debouncer returns an instance of `Illuminate\Foundation\Bus\PendingDispatch`, meaning the debouncing process itself may be assigned to a different queue or otherwise manipulated.

### Calling the debouncer

There are several ways to use the debouncer:

#### Dependency Injection

```php
use App\Jobs\MyJob;
use Mpbarlow\LaravelQueueDebouncer\Debouncer;

class MyController
{
    public function doTheThing(Debouncer $debouncer)
    {
        $debouncer->debounce(new MyJob(), 30);

        // The class is also invokable:
        $debouncer(new MyJob(), 30);
    }
}
```

#### Facade

```php
use App\Jobs\MyJob;
use Mpbarlow\LaravelQueueDebouncer\Facade\Debouncer;

Debouncer::debounce(new MyJob, 30);
```

#### Helper function

```php
use App\Jobs\MyJob;

debounce(new MyJob(), 30);
```

#### Trait

```php
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Mpbarlow\LaravelQueueDebouncer\Traits\Debounceable;

class MyJob {
    use Debounceable, Dispatchable, Queueable;
}

MyJob::debounce('foo', 'bar', 'baz', 30);
```

When monitoring the queue, you will see an entry for the package’s internal dispatcher each time the debounced job is queued, but the job itself will only run once, when the final wait time has expired.

For example, assuming the following code was ran at exactly 9am:

```php
class MyJob
{
    use Dispatchable;

    public function handle()
    {
        echo “Hello!\n”;
    }
}

$job = new MyJob();

debounce($job, now()->addSeconds(5));
sleep(3);

debounce($job, now()->addSeconds(5));
sleep(3);

debounce($job, now()->addSeconds(5));
```

you should expect the following activity on your queue:

```
[2020-03-11 09:00:05][vHmqrBYeLtK3Lbiq5TsTZxBo2igaCZHC] Processing: Closure (DispatcherFactory.php:28)
[2020-03-11 09:00:05][vHmqrBYeLtK3Lbiq5TsTZxBo2igaCZHC] Processed:  Closure (DispatcherFactory.php:28)
[2020-03-11 09:00:08][LXdzLvilh5qhew7akNDnibCjaXksG81X] Processing: Closure (DispatcherFactory.php:28)
[2020-03-11 09:00:08][LXdzLvilh5qhew7akNDnibCjaXksG81X] Processed:  Closure (DispatcherFactory.php:28)
[2020-03-11 09:00:11][MnPIqk5fCwXjiVzuwPjkkOdPPBn0xR4d] Processing: Closure (DispatcherFactory.php:28)
[2020-03-11 09:00:11][MnPIqk5fCwXjiVzuwPjkkOdPPBn0xR4d] Processed:  Closure (DispatcherFactory.php:28)
[2020-03-11 09:00:11][I2hvBoCB71qZQeD4umn5dd90zJUCAlJ5] Processing: App\Jobs\MyJob
Hello!
[2020-03-11 09:00:11][I2hvBoCB71qZQeD4umn5dd90zJUCAlJ5] Processed:  App\Jobs\MyJob
```

## Customising Behaviour

This package provides a few hooks to customise things to your needs. To override the default behaviour, you should publish the config file:

```
php artisan vendor:publish --provider="Mpbarlow\LaravelQueueDebouncer\ServiceProvider"
```

This will copy `queue_debouncer.php` to your config directory.

### Cache key provider

To identify the job being debounced, the package generates a unique key in the cache for each job type.

Two cache key providers are included:

- `Mpbarlow\LaravelQueueDebouncer\Support\CacheKeyProvider` (default): uses the config's `cache_prefix` value with either: the fully-qualified class name for class-based jobs; or a SHA1 hash of the closure for closure jobs.
- `Mpbarlow\LaravelQueueDebouncer\Support\SerializingCacheKeyProvider`: uses the config's `cache_prefix` value with a SHA1 hash of the serialized job. If you want to debounce jobs based on factors beyond their class name (for example, some internal state), this is the provider to use. This is also required if you need to debounce chains, as the default provider will debounce _all_ chains dispatched by your application as if they are the same job, regardless of what jobs are contained within.

Alternatively, you can provide your own class or closure to cover any other behaviour you might need:

If you provide a class, it should implement `Mpbarlow\LaravelQueueDebouncer\Contracts\CacheKeyProvider`. Please note that your class is responsible for fetching and prepending the cache prefix should you still desire this behaviour.

Class-based providers may be globally registered as the default provider by changing the `cache_key_provider` value in the config. Alternatively, you may "hot-swap" the provider using the `usingUniqueIdentifierProvider` method:

```php
$debouncer
    ->usingCacheKeyProvider(new CustomCacheKeyProvider())
    ->debounce($job, 10);
```

If you provide a closure, it may only be be hot-swapped:

```php
$debouncer
    ->usingCacheKeyProvider(fn () => 'my custom key')
    ->debounce($job, 10);
```

Closure providers automatically have their value prefixed by the configured `cache_prefix`. To override this behaviour, implement a class-based provider that accepts a closure in its constructor, then calls it in its `getKey` method.

### Unique identifier provider

Each time a debounced job is dispatched, a unique identifier is stored against the cache key for the job. When the wait time expires, if that identifier matches the value of the current job, the debouncer knows that no more recent instances of the job have been dispatched, and therefore it is safe to dispatch it.

The default implementation produces a UUID v4 for each dispatch. If you need to override this you may do so in the same manner as cache key providers, globally registering a class under the `unique_identifier_provider` key in the config, or hot-swapping using the `usingUniqueIdentifierProvider` method:

```php
$debouncer
    ->usingUniqueIdentifierProvider(new CustomUniqueIdentifierProvider())
    ->debounce($job, 10);

$debouncer
    ->usingUniqueIdentifierProvider(fn () => 'my custom identifier')
    ->debounce($job, 10);
```

Class-based providers should implement `Mpbarlow\LaravelQueueDebouncer\Contracts\UniqueIdentifierProvider`.

## Licence

This package is open-source software provided under the [The MIT License](https://opensource.org/licenses/MIT).
