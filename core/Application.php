<?php

namespace Core;

use Core\Support\File;
use Core\Support\Singleton;

class Application extends Singleton
{
	/**
	 * The dependency injections container for handling
	 * all application class instances and singletons as well
	 * as auto-wiring method dependencies.
	 */
	private Container $container;

	/**
	 * Stores all application service providers, which
	 * interact with the container during the registration and
	 * booting of the entire application.
	 */
	private array $providers = [];

	/**
	 * Privately constructs the application to prevent
	 * the generation of multiple instances.
	 */
	protected function __construct()
	{
		$this->container = new Container();
		$this->loadProviders();
		$this->registerProviders();
	}

	/**
	 * Gets called after the first singleton instance
	 * has been constructed. This enables the use of
	 * application methods using app() within boot methods.
	 */
	protected function __constructed()
	{
		$this->bootProviders();
	}

	/**
	 * Tries to resolve a class from the application container.
	 */
	public function resolve(string $abstract): mixed
	{
		return $this->container->resolve($abstract);
	}

	/**
	 * Instantiate all service providers for the application.
	 */
	private function loadProviders(): void
	{
		$providerClasses = File::require('config/app.php')['providers'];

		foreach ($providerClasses as $providerClass) {
			$this->providers[] = new $providerClass($this->container);
		}
	}

	/**
	 * Executes register method on all providers.
	 */
	private function registerProviders(): void
	{
		foreach ($this->providers as $provider) {
			$this->container->executeMethod([$provider, 'register']);
		}
	}

	/*
	 * Executes boot method on all providers.
	 */
	private function bootProviders(): void
	{
		foreach ($this->providers as $provider) {
			$this->container->executeMethod([$provider, 'boot']);
		}
	}
}
