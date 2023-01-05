<?php

namespace Core\Http;

use Core\Container;
use Exception;

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
	private array $routes = [
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

	/**
	 * Stores current route action tuple in following style:
	 * [Route $route, callable $action]
	 */
	private array $currentRouteAction = [];

	/**
	 * Caching alias paths if used more than once.
	 */
	private array $aliasCache = [];

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
		if (!$this->getCurrentRouteAction()) {
			return false;
		}

		[$route, $action] = $this->getCurrentRouteAction();

		return function () use ($action) {
			return $this->container->executeMethod($action);
		};
	}

	/**
	 * Registers a GET request callback.
	 */
	public function get($path, $action): Route
	{
		$cleanPath = sanitizeUriPath($path);
		$this->routes['GET'][$cleanPath] = [new Route($path), $action];
		return $this->routes['GET'][$cleanPath][0];
	}

	/**
	 * Registers a POST request callback.
	 */
	public function post($path, $action): Route
	{
		$cleanPath = sanitizeUriPath($path);
		$this->routes['POST'][$cleanPath] = [new Route($path), $action];
		return $this->routes['POST'][$cleanPath][0];
	}

	/**
	 * Registers a PUT request callback.
	 */
	public function put($path, $action): Route
	{
		$cleanPath = sanitizeUriPath($path);
		$this->routes['PUT'][$cleanPath] = [new Route($path), $action];
		return $this->routes['PUT'][$cleanPath][0];
	}

	/**
	 * Registers a DELETE request callback.
	 */
	public function delete($path, $action): Route
	{
		$cleanPath = sanitizeUriPath($path);
		$this->routes['DELETE'][$cleanPath] = [new Route($path), $action];
		return $this->routes['DELETE'][$cleanPath][0];
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

	public function executeAllMiddleware(): void
	{
		$this->executeGlobalMiddleware();
		$this->executeRouteMiddleware($this->getRoute());
	}

	/**
	 * Retrieve route by alias name.
	 */
	public function getRoute(string $alias = ''): Route
	{
		if (empty($alias)) {
			[$route] = $this->getCurrentRouteAction();
			return $route;
		}

		if (!array_key_exists($alias, $this->aliasCache)) {
			throw new Exception("Could not find route with name '$alias'");
		}

		return $this->aliasCache[$alias];
	}

	/**
	 * Get tuple of current route action in form: [$route, $action]
	 */
	private function getCurrentRouteAction(): array|false
	{
		if (!empty($this->currentRouteAction)) {
			return $this->currentRouteAction;
		}

		$request = $this->container->resolve(Request::class);

		$path = $request->getSanitizedPath();
		$mappings = $this->getActionMappings($request);

		return array_key_exists($path, $mappings) ? $mappings[$path] : false;
	}


	/**
	 * Executes all custom route middlewares.
	 */
	private function executeRouteMiddleware(Route $route): void
	{
		foreach ($route->middlewares as $middleware) {
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
		$this->cacheAliases();
	}

	/**
	 * Cache aliases for future references.
	 */
	private function cacheAliases(): void
	{
		foreach ($this->routes as $methodMapping) {
			foreach ($methodMapping as $routeBinding) {
				$route = $routeBinding[0];
				if (!empty($route->alias)) {
					$this->aliasCache[$route->alias] = $route;
				}
			}
		}
	}

	/**
	 * Returns callback mappings based on current request method.
	 */
	private function getActionMappings(Request $request): array
	{
		return $this->routes[$request->getMethod()];
	}
}