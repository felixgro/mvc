<?php

namespace Core\Http;

use Core\Providers\ServiceProvider;
use Core\Support\Event;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;


class HttpServiceProvider extends ServiceProvider
{
	private string $routeDefinitionFile = 'app/routes.php';

	public function register()
	{
		$this->app->bind(ControllerResolverInterface::class, function ($c) {
			return $c->resolve(ControllerResolver::class);
		});

		$this->app->singleton(Event::class);

		$this->app->singleton(Request::class, function () {
			return Request::createFromGlobals();
		});

		$this->app->singleton(Response::class);

		$this->app->singleton(Router::class, function ($c) {
			$routeDefinitionPath = path($this->routeDefinitionFile);
			return new Router($c, $routeDefinitionPath);
		});
	}

	public function boot(Router $router, Event $dispatcher)
	{
		$router->loadRoutes();

		// Call global middleware before each request
		$dispatcher->addListener('kernel.request', function () use ($router) {
			$router->executeAllMiddleware();
		});
	}

	private function bindRouter(): void
	{

	}
}