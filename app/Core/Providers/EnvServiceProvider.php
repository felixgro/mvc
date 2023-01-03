<?php

namespace App\Core\Providers;

use App\Core\Container;
use App\Core\Support\Env;
use App\Core\Support\Config;

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