<?php

namespace App\Providers;

use Core\Providers\ServiceProvider;
use Core\Http\Middleware\ViteMiddleware;
use Core\Http\Router;

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
			// ...
		]);

		if ($this->inEnvironment('development')) {
			$router->addGlobalMiddleware(ViteMiddleware::class);
		}
	}
}
