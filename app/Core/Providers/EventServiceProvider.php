<?php

namespace App\Core\Providers;

use App\Core\Services\EventDispatcher;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;


class EventServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->container->bind(EventDispatcherInterface::class, function () {
			return $this->container->resolve(EventDispatcher::class);
		});
	}

	public function boot()
	{
		//
	}
}