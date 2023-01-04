<?php

namespace Core\Providers;

use Core\Services\Config;
use Core\Services\Env;

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