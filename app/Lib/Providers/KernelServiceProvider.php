<?php

namespace App\Lib\Providers;

use App\Lib\Contracts\ServiceProvider;
use App\Lib\Core\Container;
use App\Lib\Core\EventDispatcher;
use App\Lib\Http\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class KernelServiceProvider implements ServiceProvider
{
	public static function register(Container $c)
	{
		// As long as the container doesn't support interface mappings,
		// all interfaces have to get binded manually.
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