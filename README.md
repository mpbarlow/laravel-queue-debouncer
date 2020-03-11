# Laravel Queue Debouncer
### Easy queue job debouncing

## Requirements
* Laravel >= 6.0
* An async queue driver

**Note:** v2.0 requires at least Laravel 6. For Laravel 5.5 ..< 6.0 support, check out [v1.0.2](https://github.com/mpbarlow/laravel-queue-debouncer/tree/1.0.2)

## Installation
`composer require mpbarlow/laravel-queue-debouncer`

## Background
This package allows any queue job in your Laravel application to be debounced, meaning that no matter how many times it’s dispatched within the specified timeout window, it will only actually be ran once.

For example, imagine you want to send an email each time a user changes their contact details, but you don’t want to spam them if they make several changes in a short space of time. Debouncing the job with a five minute wait would ensure they only receive a single email, no matter how many changes they make within that five minutes.

Version 2.0 is a complete rewrite and brings numerous improvements to the capabilities and ergonomics of the package, as well as thorough test coverage.

## Usage
Debounced jobs can largely be treated like any other dispatched job. The debouncer takes two arguments, the actual `$job` you want to run, and the `$wait` period. 

As with a regular dispatch, `$job` can be either a class implementing `Illuminate\Foundation\Bus\Dispatchable` or a closure. `$wait` accepts any argument that the `delay` method on a dispatch accepts (i.e. a `DateTimeInterface` or a number of seconds). 

The debouncer returns an instance of `Illuminate\Foundation\Bus\PendingDispatch`, meaning the debouncing process itself may be assigned to a different queue or otherwise manipulated.

### Calling the debouncer
There are several ways to initiate debouncing of a job:

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

Of course, you may substitute the job class for a closure.

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
This package provides a few knobs and dials to customise things to your needs. To override the default behaviour, you should publish the config file:

```
php artisan vendor:publish --provider="Mpbarlow\LaravelQueueDebouncer\ServiceProvider"
```

This will copy `queue_debouncer.php` to your config directory.

### Cache key provider
To support multiple jobs being debounced, the package generates a unique key in the cache for each job type. This key is used as a lookup when the wait for a given dispatch expires, to determine whether the job should be ran or not.

The default implementation works as follows:
* For class-based jobs, use the fully-qualified class name
* For closure-based jobs, compute the SHA1 hash of a reflected representation of the closure
* Prepend the `cache_prefix` value from the config, separated by a colon (‘:’)

If the default behaviour works for you, but you’d like to use a different cache prefix, you may simply override that value.

Alternatively, you can provide your own class to produce a key for a given job. This is useful in cases where you have jobs that are implemented as different classes but should be considered the same job for the purposes of debouncing.

Your provider class must implement `Mpbarlow\LaravelQueueDebouncer\Contracts\CacheKeyProvider`. Please note that your class is responsible for fetching and prepending the cache prefix should you still desire this behaviour—it will not be added automatically.

**Note:** Because of the limited ways we have to produce a value representation of a closure, only the _exact same closure_ will produce the same cache key. So, while closures originating from the same line in the same file will produce the same key on subsequent requests, two closures with the same content dispatched from different places will not.

### Unique identifier provider
Each time a debounced job is dispatched, a unique identifier is stored against the cache key for the job. When the wait time expires, if that identifier matches the value inserted, the debouncer knows that no more recent instances of the job have been dispatched, and therefore it is safe to dispatch it.

The default implementation produces a UUID v4 for each dispatch. If you need to override this you may do so via the config. As with the cache key provider, your class must implement  `Mpbarlow\LaravelQueueDebouncer\Contracts\UniqueIdentifierProvider`.

## Licence
This package is open-source software provided under the [The MIT License](https://opensource.org/licenses/MIT).
