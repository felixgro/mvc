<?php

namespace App\Lib\Providers;

use App\Lib\Contracts\ServiceProvider;
use App\Lib\Core\Container;
use App\Lib\Core\EventDispatcher;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class MiddlewareServiceProvider implements ServiceProvider
{
	public static array $globalMiddleware = [];

	/**
	 * Load all lib middlewares
	 * TODO: Add support for custom defined middlewares
	 */
	public static function register(Container $c)
	{
		$middlewareFiles = glob(path('app/Lib/Http/Middleware/*.php'));

		foreach ($middlewareFiles as $file) {
			self::$globalMiddleware[] = str_replace('/', '\\', ucfirst(substr($file, 0, -4)));
		}
	}

	/**
	 * Register request listener which runs all defined middlewares.
	 */
	public static function boot(Container $c)
	{
		$dispatcher = $c->resolve(EventDispatcher::class);

		$dispatcher->addListener('kernel.request', function (RequestEvent $event) {
			$request = $event->getRequest();
			foreach (self::$globalMiddleware as $middleware) {
				($middleware)::handle($request);
			}
		});
	}
}