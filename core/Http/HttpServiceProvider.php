<?php

namespace Core\Http;

use Core\Providers\ServiceProvider;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;


class HttpServiceProvider extends ServiceProvider
{
	private string $routeDefinitionFile = 'app/routes.php';

	public function register()
	{
		$this->bindKernelContract();
		$this->bindRequest();
		$this->bindResponse();
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

	private function bindResponse(): void
	{
		$this->app->singleton(Response::class);
	}

	private function bindRouter(): void
	{
		$this->app->singleton(Router::class, function ($c) {
			$routeDefinitionPath = path($this->routeDefinitionFile);
			return new Router($c, $routeDefinitionPath);
		});
	}
}