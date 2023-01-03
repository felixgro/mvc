<?php

namespace Core\Providers;

use Core\Http\Request;
use Core\Http\Router;
use Core\Services\ControllerResolver;
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
		$this->container->bind(ControllerResolverInterface::class, function () {
			return $this->container->resolve(ControllerResolver::class);
		});
	}

	private function bindRequest(): void
	{
		$this->container->bind(Request::class, function () {
			return Request::createFromGlobals();
		});
	}

	private function bindRouter(): void
	{
		$this->container->bind(Router::class, function () {
			return new Router($this->container, path($this->routeDefinitionFile));
		});
	}
}