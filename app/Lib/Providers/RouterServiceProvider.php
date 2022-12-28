<?php

namespace App\Lib\Providers;

use App\Lib\Contracts\ServiceProvider;
use App\Lib\Core\Container;
use App\Lib\Http\Router;

class RouterServiceProvider implements ServiceProvider
{
	/**
	 * Instantiate application router.
	 */
	public static function register(Container $c)
	{
		$c->bind(Router::class, function () use ($c) {
			return new Router($c, 'app/routes.php');
		});
	}

	/**
	 * Register all routes within the specified route definition file.
	 */
	public static function boot(Container $c)
	{
		$c->resolve(Router::class)->loadRoutes();
	}
}