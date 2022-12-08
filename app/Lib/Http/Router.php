<?php

namespace App\Lib\Http;

class Router
{
	private array $actions = [
		'GET' => [],
		'POST' => [],
		'PUT' => [],
		'DELETE' => []
	];

	private string $routeDefinitionFile;

	public function __construct(string $routeDefinitionFile)
	{
		$this->routeDefinitionFile = $routeDefinitionFile;
	}

	public function loadRoutes(): void
	{
		$router = $this;
		require_once $this->routeDefinitionFile;
	}

	public function getAction(Request $request): callable|false
	{
		$path = $request->getSanitizedPath();
		$mappings = $this->getActionMappings($request);

		if (array_key_exists($path, $mappings)) {
			return $mappings[$path];
		}

		return false;
	}

	/**
	 * Registers a GET request action.
	 */
	public function get($path, $action): void
	{
		$path = sanitizeUriPath($path);
		$this->actions['GET'][$path] = $action;
	}

	/**
	 * Registers a POST request action.
	 */
	public function post($path, $action): void
	{
		$path = sanitizeUriPath($path);
		$this->actions['POST'][$path] = $action;
	}

	/**
	 * Registers a PUT request action.
	 */
	public function put($path, $action): void
	{
		$path = sanitizeUriPath($path);
		$this->actions['PUT'][$path] = $action;
	}

	/**
	 * Registers a DELETE request action.
	 */
	public function delete($path, $action): void
	{
		$path = sanitizeUriPath($path);
		$this->actions['DELETE'][$path] = $action;
	}

	/**
	 * Returns current mappings based on request method.
	 */
	private function getActionMappings(Request $request): array
	{
		return $this->actions[$request->getMethod()];
	}
}