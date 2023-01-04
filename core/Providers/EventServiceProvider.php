<?php

namespace Core\Providers;

use Core\Support\Event;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;


class EventServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->app->singleton(EventDispatcherInterface::class, function ($c) {
			return $c->resolve(Event::class);
		});
	}

	public function boot()
	{
		//
	}
}