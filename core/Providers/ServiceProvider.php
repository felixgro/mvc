<?php

namespace Core\Providers;

use Core\Application;

/*
 * A service provider is used to interact with the application service container.
 * It may be used to bind classes within the container during registration.
 * Then, the newly bound classes may be resolved & configured during the boot-phase of the application.
 */

abstract class ServiceProvider
{
	/**
	 * Store a reference to the dependency-injection container.
	 * This container can be used on each service provider to bind
	 * classes within the container during registration and boot events.
	 */
	public function __construct(
		protected Application $app
	)
	{
	}

	/**
	 * This method gets executed during the instantiation phase of the application.
	 * In this phase, none of the application services are yet available.
	 * Important: Calling the global app() method during the registration could cause an infinite recursion.
	 *
	 * public function register()
	 * {
	 * //
	 * }
	 */


	/**
	 * This Method gets executed after the application has been instantiated.
	 * All method parameters will get resolved using the application dependency-injection container.
	 * All application services are available using the app() helper.
	 *
	 * public function boot()
	 * {
	 * //
	 * }
	 * */

	/**
	 * Checks if application is in specified environment.
	 */
	protected function inEnvironment(string $env): bool
	{
		return config('app.env') === $env;
	}

	/**
	 * Checks if application gets executed as through the command line.
	 */
	protected function inTerminal(): bool
	{
		return php_sapi_name() === 'cli';
	}
}