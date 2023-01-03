<?php

namespace App\Core\Providers;

use App\Core\Http\Request;
use App\Core\Http\Router;
use App\Core\Support\EventDispatcher;
use App\Core\Support\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;


class HttpServiceProvider extends ServiceProvider
{
	private string $routeDefinitionFile = 'app/routes.php';

	public function register()
	{
		$this->bindKernelContracts();
		$this->bindRequest();
		$this->bindRouter();
	}

	public function boot(Router $router)
	{
		$router->loadRoutes();
	}

	private function bindKernelContracts(): void
	{
		$this->container->bind(EventDispatcherInterface::class, function () {
			return $this->container->resolve(EventDispatcher::class);
		});

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