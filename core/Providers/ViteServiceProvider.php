<?php

namespace Core\Providers;

use Core\Support\Vite;

class ViteServiceProvider extends ServiceProvider
{
	public function register()
	{
		//
	}

	public function boot()
	{
		$this->app->bind(Vite::class, function () {
			$host = 'http://' . config('vite.host') . ':' . config('vite.port');
			$manifest = path(config('vite.manifest'));

			return new Vite($host, $manifest);
		});
	}
}