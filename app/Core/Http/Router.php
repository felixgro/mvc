<?php

namespace App\Core\Http;

use App\Core\Container;

class Router
{
	/**
	 * Collection for mapping all defined route actions,
	 * of type callable, to the correlating request method.
	 * This should be a 2-level multidimensional array which
	 * also defines the routes path as second level key:
	 *  [
	 *        'GET' => [
	 *            'my-route-path' => fn () { ... };
	 *        ],
	 *        ...
	 *  ]
	 */
	private array $actions = [
		'GET' => [],
		'POST' => [],
		'PUT' => [],
		'DELETE' => []
	];

	/**
	 * Path to the file, which defines all
	 * application routes and middlewares.
	 */
	private string $routeDefinitionFile;

	/**
	 * Collection of middleware, which gets
	 * executed for every request.
	 */
	private array $globalMiddleware = [];

	/**
	 * Reference to current application container instance.
	 * This is needed to resolve controller methods using the
	 * containers dependency-injection and auto-wiring features.
	 */
	private Container $container;

	/*
	 * Requires the absolute path to the route definition file.
	 * When executing, this file can interact with the current
	 * class instance by using the global $router variable.
	 */
	public function __construct(Container $container, string $routeDefinitionFile)
	{
		$this->container = $container;
		$this->routeDefinitionFile = $routeDefinitionFile;
	}

	/**
	 * Tries to provide a callback action by reading the request's path.
	 * First, the callback executes the middleware (if set), then the controller action.
	 * Returns false if no action found.
	 */
	public function getAction(Request $request): callable|false
	{
		$path = $request->getSanitizedPath();
		$mappings = $this->getActionMappings($request);

		if (array_key_exists($path, $mappings)) {
			return function () use ($mappings, $path) {
				$this->executeGlobalMiddleware();
				return $this->container->executeMethod($mappings[$path]);
			};
		}

		// TODO: Refactor asset path to use global middleware through service provider
		if (str_starts_with($path, '/assets')) {
			return function () {
				$this->executeGlobalMiddleware();
			};
		}

		return false;
	}

	/**
	 * Registers a GET request callback.
	 */
	public function get($path, $action): void
	{
		$path = sanitizeUriPath($path);
		$this->actions['GET'][$path] = $action;
	}

	/**
	 * Registers a POST request callback.
	 */
	public function post($path, $action): void
	{
		$path = sanitizeUriPath($path);
		$this->actions['POST'][$path] = $action;
	}

	/**
	 * Registers a PUT request callback.
	 */
	public function put($path, $action): void
	{
		$path = sanitizeUriPath($path);
		$this->actions['PUT'][$path] = $action;
	}

	/**
	 * Registers a DELETE request callback.
	 */
	public function delete($path, $action): void
	{
		$path = sanitizeUriPath($path);
		$this->actions['DELETE'][$path] = $action;
	}

	/**
	 * Registers global middlewares.
	 */
	public function addGlobalMiddleware(array|string $middleware): void
	{
		if (is_string($middleware)) {
			$this->globalMiddleware[] = $middleware;
		} else {
			$this->globalMiddleware = array_merge($this->globalMiddleware, $middleware);
		}
	}

	/**
	 * Executes all global middlewares, if any set.
	 */
	public function executeGlobalMiddleware(): void
	{
		foreach ($this->globalMiddleware as $middleware) {
			$this->executeMiddleware($middleware);
		}
	}

	/*
	 * Executes a single middleware.
	 */
	public function executeMiddleware(string $middleware): void
	{
		$res = $this->container->executeMethod([$middleware, '__invoke']);

		if (isset($res) && $res instanceof Response) {
			exit($res->send());
		}
	}

	/**
	 * Reads & stores all routes from routes.php
	 * Defines own instance as $router variable for better readability
	 */
	public function loadRoutes(): void
	{
		$router = $this;
		require_once $this->routeDefinitionFile;
	}

	/**
	 * Returns callback mappings based on current request method.
	 */
	private function getActionMappings(Request $request): array
	{
		return $this->actions[$request->getMethod()];
	}
}