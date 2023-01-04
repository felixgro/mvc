<?php

namespace Core\Providers;

use Core\Support\Config;
use Core\Support\Env;

class EnvServiceProvider extends ServiceProvider
{
	private string $configDir = 'config/';

	public function register()
	{
		$this->app->singleton(Env::class);

		$this->app->singleton(Config::class, function () {
			$configDir = path($this->configDir);
			return new Config($configDir);
		});
	}

	public function boot()
	{
		//
	}
}