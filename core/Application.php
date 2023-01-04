<?php

namespace Core;

use Core\Facades\File;
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
	}

	/**
	 * Gets called after the first singleton instance
	 * has been constructed. This enables the use of
	 * application methods using app() within provider methods.
	 */
	protected function __constructed()
	{
		$this->loadProviders();
		$this->registerProviders();
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
	 * Binds a class in the application container.
	 */
	public function bind(string $abstract, callable $factory): void
	{
		$this->container->bind($abstract, $factory);
	}

	/**
	 * Binds a singleton in the application container.
	 */
	public function singleton(string $abstact, callable $factory = null): void
	{
		$this->container->singleton($abstact, $factory);
	}

	/**
	 * Instantiate all service providers for the application.
	 */
	private function loadProviders(): void
	{
		$ProviderClasses = File::require('config/app.php')['providers'];

		foreach ($ProviderClasses as $ProviderClass) {
			$this->providers[] = new $ProviderClass($this);
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
