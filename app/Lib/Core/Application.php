<?php

namespace App\Lib\Core;

use App\Lib\Http\Kernel;
use App\Lib\Http\Request;

class Application
{
	private static Application $instance;

	private Container $container;

	private array $providers = [];


	private function __construct()
	{
		$this->container = new Container();
		$this->loadProviders();
		$this->registerProviders();
	}

	public static function getInstance(): Application
	{
		if (!isset(self::$instance)) {
			self::$instance = new Application();
			self::$instance->bootProviders();
		}

		return self::$instance;
	}

	public function handleRequest()
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

	public function bind(string $abstract, callable $factory)
	{
		$this->container->bind($abstract, $factory);
	}

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
		foreach (glob(path('app/Lib/Providers/*ServiceProvider.php')) as $providerPath) {
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
