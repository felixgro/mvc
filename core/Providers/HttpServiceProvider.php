<?php

namespace Core\Providers;

use Core\Http\ControllerResolver;
use Core\Http\Request;
use Core\Http\Router;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;


class HttpServiceProvider extends ServiceProvider
{
	private string $routeDefinitionFile = 'app/routes.php';

	public function register()
	{
		$this->bindKernelContract();
		$this->bindRequest();
		$this->bindRouter();
	}

	public function boot(Router $router)
	{
		$router->loadRoutes();
	}

	private function bindKernelContract(): void
	{
		$this->app->singleton(ControllerResolverInterface::class, function ($c) {
			return $c->resolve(ControllerResolver::class);
		});
	}

	private function bindRequest(): void
	{
		$this->app->singleton(Request::class, function () {
			return Request::createFromGlobals();
		});
	}

	private function bindRouter(): void
	{
		$this->app->singleton(Router::class, function ($c) {
			$routeDefinitionPath = path($this->routeDefinitionFile);
			return new Router($c, $routeDefinitionPath);
		});
	}
}