<?php

namespace App\Core\Providers;

use App\Core\Container;
use App\Core\Contracts\ServiceProvider;
use App\Core\Http\Request;

class RequestServiceProvider implements ServiceProvider
{
	/**
	 * Instantiate request using global php variables.
	 */
	public static function register(Container $c)
	{
		$c->bind(Request::class, function () {
			return Request::createFromGlobals();
		});
	}

	public static function boot(Container $c)
	{
		//
	}
}