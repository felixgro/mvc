<?php

namespace App\Core\Providers;

use App\Core\Container;
use App\Core\Contracts\ServiceProvider;
use App\Core\Support\ControllerResolver;
use App\Core\Support\EventDispatcher;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class KernelServiceProvider implements ServiceProvider
{
	/**
	 * As long as the container doesn't support interface mappings,
	 * all required kernel interfaces have to get bound manually.
	 * TODO: Implement container interface mappings
	 */
	public static function register(Container $c)
	{
		$c->bind(EventDispatcherInterface::class, function () use ($c) {
			return $c->resolve(EventDispatcher::class);
		});

		$c->bind(ControllerResolverInterface::class, function () use ($c) {
			return $c->resolve(ControllerResolver::class);
		});
	}

	public static function boot(Container $c)
	{
		//
	}
}