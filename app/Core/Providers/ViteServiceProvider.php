<?php

namespace App\Core\Providers;

use App\Core\Container;
use App\Core\Contracts\ServiceProvider;
use App\Core\Support\Vite;

class ViteServiceProvider implements ServiceProvider
{
	public static function register(Container $c)
	{
		//
	}

	public static function boot(Container $c)
	{
		$c->bind(Vite::class, function () {
			$host = 'http://' . config('vite.host') . ':' . config('vite.port');
			$manifest = path(config('vite.manifest'));

			return new Vite($host, $manifest);
		});
	}
}