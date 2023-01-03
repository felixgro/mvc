<?php

namespace App\Core\Providers;

use App\Core\Support\Vite;

class ViteServiceProvider extends ServiceProvider
{
	public function register()
	{
		//
	}

	public function boot()
	{
		$this->container->bind(Vite::class, function () {
			$host = 'http://' . config('vite.host') . ':' . config('vite.port');
			$manifest = path(config('vite.manifest'));

			return new Vite($host, $manifest);
		});
	}
}