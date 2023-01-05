<?php

namespace Core\Providers;

use Core\Support\Vite;
use Core\View\View;

class ViteServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->app->singleton(Vite::class, function () {
			$host = 'http://' . config('vite.host') . ':' . config('vite.port');
			$manifest = path(config('vite.manifest'));

			return new Vite($host, $manifest);
		});
	}

	public function boot(Vite $vite, View $view)
	{
		$view->registerFunction('vite', function (string $entry) use ($vite) {
			return $vite->asset($entry);
		});
	}
}