<?php

return [
	/**
	 * Name of the application.
	 */
	'name' => env('APP_NAME', 'MVC'),

	/**
	 * The environment, in which the application is currently running.
	 * Defaults to "production" if no value set.
	 */
	'env' => env('APP_ENV', 'production'),

	/**
	 * Specify, if the application should show errors in the frontend.
	 */
	'debug' => env('APP_DEBUG', false),

	/**
	 * Providers which are loaded during boot phase of application.
	 * These providers are used to load the application functionality
	 * in the container during registration and boot events.
	 * The order directly correlates to the order of execution.
	 */
	'providers' => [
		# Core Service Providers (Don't touch those if you don't know what you're doing)
		'App\Core\Providers\EnvServiceProvider',
		'App\Core\Providers\KernelServiceProvider',
		'App\Core\Providers\RequestServiceProvider',
		'App\Core\Providers\RouterServiceProvider',
		'App\Core\Providers\MiddlewareServiceProvider',
		'App\Core\Providers\ViteServiceProvider',

		# Custom application Service Providers (add as many as you like)
		'App\Providers\AppServiceProvider'
	]
];
