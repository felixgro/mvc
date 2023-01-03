<?php

namespace App\Providers;

use App\Core\Providers\ServiceProvider;
use App\Core\Http\Middleware\AuthMiddleware;
use App\Core\Http\Middleware\ViteMiddleware;
use App\Core\Http\Router;

class AppServiceProvider extends ServiceProvider
{
	public function register()
	{
		//
	}

	public function boot(Router $router)
	{
		// setup global application middleware
		$router->addGlobalMiddleware([
			AuthMiddleware::class,
		]);

		if ($this->inEnvironment('development')) {
			$router->addGlobalMiddleware(ViteMiddleware::class);
		}
	}
}
