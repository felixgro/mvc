<?php

namespace App\Core\Providers;

use App\Core\Container;
use App\Core\Contracts\ServiceProvider;
use App\Core\Support\Env;
use App\Core\Support\Config;

class EnvServiceProvider implements ServiceProvider
{
	public static function register(Container $c)
	{
		$c->bind(Env::class, function () {
			return Env::getInstance();
		});

		$c->bind(Config::class, function () {
			return new Config(path('../config/'));
		});
	}

	public static function boot(Container $c)
	{
		//
	}
}