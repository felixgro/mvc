<?php

namespace Core\View;

use Core\Auth\Auth;
use Core\Providers\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
	private array $config;

	public function register()
	{
		$this->config = config('view');

		$this->app->singleton(View::class, function () {
			$directoryPath = path($this->config['directory']);
			return new View($directoryPath, $this->config['extension']);
		});
	}

	public function boot(View $view)
	{
		$view->addFolder('layouts', path($this->config['directory'], 'layouts'));

		$view->registerFunction('auth', function () {
			return app(Auth::class);
		});

		if (config('view.framework') === 'vue') {
			$view->registerFunction('vue', function ($class = '') {
				return "<div class='vue-app $class'>";
			});

			$view->registerFunction('endvue', function () {
				return '</div>';
			});
		}
	}
}