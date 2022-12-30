<?php

namespace App\Core;

use App\Core\Http\Request;
use App\Core\Support\Singleton;

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

	protected function __constructed()
	{
		$this->bootProviders();
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
		$this->providers = array_reverse((require('../config/app.php'))['providers']);
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
