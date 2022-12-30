<?php

namespace App\Core;

use App\Core\Http\Request;
use App\Core\Lib\Kernel;

class Application
{
	/**
	 * Stores the one and only application instance.
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
	 * Privately constructs the application to prevent
	 * the generation of multiple instances.
	 */
	private function __construct()
	{
		$this->container = new Container();
		$this->loadProviders();
		$this->registerProviders();
	}

	/**
	 * Retrieve the one and only singleton
	 * instance of the application class.
	 */
	public static function getInstance(): Application
	{
		if (!isset(self::$instance)) {
			self::$instance = new Application();
			self::$instance->bootProviders();
		}

		return self::$instance;
	}

	/**
	 * Main entry point for the incoming http request.
	 * This should be the only function call in the root
	 * index.php file, and it's only dependency is the correct
	 * vendor autoload file.
	 */
	public function handleRequest(): void
	{
		$kernel = $this->container->resolve(Kernel::class);
		$request = $this->container->resolve(Request::class);

		try {
			$response = $kernel->handle($request);
			$response->send();
			$kernel->terminate($request, $response);
		} catch (\Throwable $exception) {
			dump('exception in http kernel:');
			dd($exception);
		}
	}

	/**
	 * Tries to bind a factory to an abstract using the
	 * application container.
	 */
	public function bind(string $abstract, callable $factory): void
	{
		$this->container->bind($abstract, $factory);
	}

	/**
	 * Tries to resolve a class from the application container.
	 */
	public function get(string $abstract): mixed
	{
		return $this->container->resolve($abstract);
	}

	/**
	 * Loads all service providers for the application.
	 */
	private function loadProviders(): void
	{
		// first, all core providers since they're essential for a working application
		foreach (glob(path('app/Core/Providers/*ServiceProvider.php')) as $providerPath) {
			$this->providers[] = str_replace('/', '\\', ucfirst(substr($providerPath, 0, -4)));
		}

		// afterwards, all custom defined providers
		$this->providers = array_merge(
			$this->providers,
			(require_once 'config/app.php')['providers']
		);
	}

	/**
	 * Executes static register method on all providers.
	 */
	private function registerProviders(): void
	{
		foreach ($this->providers as $provider) {
			($provider)::register($this->container);
		}
	}

	/*
	 * Executes static boot method on all providers.
	 */
	private function bootProviders(): void
	{
		foreach ($this->providers as $provider) {
			($provider)::boot($this->container);
		}
	}
}
