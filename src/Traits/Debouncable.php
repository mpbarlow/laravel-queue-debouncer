<?php

namespace Mpbarlow\LaravelQueueDebouncer\Traits;

use Mpbarlow\LaravelQueueDebouncer\Facade\Debouncer;

trait Debouncable
{
	/**
	 * Dispatch the job with the given arguments.
	 *
	 * @return \Illuminate\Foundation\Bus\PendingDispatch
	 */
	public static function debounce()
	{
		$jobProperties = func_get_args();
		$wait = array_pop($jobProperties);
		
		return Debouncer::debounce(new static(...$jobProperties), $wait);
	}

}