<?php

namespace Core\Providers;

use Core\Services\Config;
use Core\Services\Env;

class EnvServiceProvider extends ServiceProvider
{
	private string $configDir = 'config/';

	public function register()
	{
		$this->container->bind(Env::class, function () {
			return Env::getInstance();
		});

		$this->container->bind(Config::class, function () {
			return new Config(path($this->configDir));
		});
	}

	public function boot()
	{
		//
	}
}