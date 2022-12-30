<?php

return [
	/**
	 * Name of the application.
	 */
	'name' => 'MVC',

	/**
	 * The environment, in which the application is currently running.
	 * Defaults to "production" if no value set.
	 */
	'env' => 'development',

	/**
	 * Specify, if the application should show errors in the frontend.
	 * Be careful: This can be a security risk on production systems.
	 */
	'debug' => true,

	/**
	 * Providers which are loaded during boot phase of application.
	 */
	'providers' => [
		'App\Providers\AppServiceProvider'
	]
];
