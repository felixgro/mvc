<?php

namespace App\Core\Providers;

use App\Core\Contracts\ServiceProvider;
use App\Core\Container;
use App\Core\Http\Router;

class RouterServiceProvider implements ServiceProvider
{
	/**
	 * Instantiate application router.
	 */
	public static function register(Container $c)
	{
		$c->bind(Router::class, function () use ($c) {
			return new Router($c, path('../app/routes.php'));
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