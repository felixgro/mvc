<?php

namespace Core;

use Core\Facades\File;

class Application
{
	/**
	 * Reference to the one and only application instance.
	 */
	private static Application $instance;

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
	 * Privately constructs the application to prevent the generation
	 * of multiple instances and enforce the use of the getInstance() static method.
	 */
	private function __construct()
	{
		$this->container = new Container();
	}

	/**
	 * Retrieves singleton instance of the application. Acts as entry point
	 * for application & container requests and also used by the global app() function.
	 * This should be the only singleton instance in the whole application which is
	 * directly instantiated through class methods. Any other application singleton
	 * should directly be registered as a singleton from within a service container.
	 */
	public static function getInstance(): Application
	{
		if (!isset(static::$instance)) {
			// Generate application using all specified providers.
			static::$instance = new Application();
			static::$instance->loadProviders();
			static::$instance->registerProviders();
			static::$instance->bootProviders();
		}

		return static::$instance;
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
