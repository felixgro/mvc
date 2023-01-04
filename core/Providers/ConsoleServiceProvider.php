<?php

namespace Core\Providers;


use Core\Console\Kernel;
use Symfony\Component\Console\Application;

class ConsoleServiceProvider extends ServiceProvider
{
	public function register()
	{
		//
	}

	public function boot()
	{
		if (!config('console.enable')) {
			return;
		};

		$this->app->singleton(Kernel::class, function () {
			$kernel = new Kernel('MVC', '1.0.0');

			foreach (config('console.commands') as $Command) {
				$kernel->add(new $Command());
			}

			return $kernel;
		});
	}
}