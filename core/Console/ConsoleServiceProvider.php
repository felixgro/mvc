<?php

namespace Core\Console;


use Core\Providers\ServiceProvider;

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