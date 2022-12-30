<?php

namespace App\Providers;

use App\Core\Container;
use App\Core\Contracts\ServiceProvider;
use App\Core\Http\Middleware\AuthMiddleware;
use App\Core\Http\Middleware\ViteMiddleware;
use App\Core\Http\Router;

class AppServiceProvider implements ServiceProvider
{
	public static function register(Container $c)
	{
		//
	}

	public static function boot(Container $c)
	{
		// setup global application middleware
		$router = $c->resolve(Router::class);

		$router->setGlobalMiddleware([
			AuthMiddleware::class,
			ViteMiddleware::class
		]);
	}
}
