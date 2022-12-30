<?php

namespace App\Core\Providers;

use App\Core\Container;
use App\Core\Contracts\ServiceProvider;

class MiddlewareServiceProvider implements ServiceProvider
{
	public static array $globalMiddleware = [];

	/**
	 * Load all lib middlewares
	 * TODO: Add support for custom defined middlewares
	 */
	public static function register(Container $c)
	{
		//
	}

	/**
	 * Register request listener which runs all defined middlewares.
	 */
	public static function boot(Container $c)
	{
		/*
		$dispatcher = $c->resolve(EventDispatcher::class);

		$dispatcher->addListener('kernel.request', function (RequestEvent $event) {
			$request = $event->getRequest();
			foreach (self::$globalMiddleware as $middleware) {
				($middleware)::handle($request);
			}
		});
		*/
	}
}