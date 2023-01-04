<?php

namespace Core\Providers;

use Core\Services\EventDispatcher;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;


class EventServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->app->singleton(EventDispatcherInterface::class, function ($c) {
			return $c->resolve(EventDispatcher::class);
		});
	}

	public function boot()
	{
		//
	}
}