<?php

namespace App\Lib\Providers;

use App\Lib\Contracts\ServiceProvider;
use App\Lib\Core\Container;
use App\Lib\Http\Request;

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