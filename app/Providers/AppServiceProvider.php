<?php

namespace App\Providers;

use App\Core\Container;
use App\Core\Contracts\ServiceProvider;
use App\Core\Http\Middleware\AuthMiddleware;
use App\Core\Http\Middleware\ViteMiddleware;
use App\Core\Http\Router;
use App\Core\Support\Config;

class AppServiceProvider implements ServiceProvider
{
	public static function register(Container $c)
	{
		$c->bind(Config::class, function () {
			$configDir = path('../config/');
			return new Config($configDir);
		});
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
