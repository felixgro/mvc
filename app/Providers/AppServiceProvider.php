<?php

namespace App\Providers;

use App\Core\Container;
use App\Core\Contracts\ServiceProvider;
use App\Core\Http\Middleware\AuthMiddleware;
use App\Core\Http\Middleware\ViteMiddleware;
use App\Core\Http\Router;

use function config;

class AppServiceProvider implements ServiceProvider
{
	public static function register(Container $c)
	{
		//
	}

	public static function boot(Container $c)
	{
		$router = app(Router::class);

		// setup global application middleware
		$router->addGlobalMiddleware([
			AuthMiddleware::class,
		]);

		if (config('app.env') === 'development') {
			$router->addGlobalMiddleware(ViteMiddleware::class);
		}
	}
}
