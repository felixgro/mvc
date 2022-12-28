<?php

namespace App\Lib\Core;

use Exception;
use ReflectionClass;
use ReflectionMethod;

class Container
{
	/**
	 * Collection for all active bindings.
	 */
	private array $bindings = [];

	/**
	 * Collection for all registered singletons.
	 */
	private array $singletons = [];

	/**
	 * Resolves a value based of an abstract from the container.
	 * If none stored, the containers tries to instantiate a class
	 * based on the given abstract on its own. If one found, the container
	 * registers the new object as a singleton for future reference.
	 */
	public function resolve(string $abstract): mixed
	{
		// $this->debugResolving($abstract);
		if ($this->hasBinding($abstract)) {
			return $this->resolveBinding($abstract);
		}

		return $this->createNewInstance($abstract);
	}

	/**
	 * Registers a new factory in the container. This factory callback
	 * gets executed when the correlating abstract is resolved. By default,
	 * the execution result gets cached for future references.
	 */
	public function bind(string $abstract, callable $factory, bool $singleton = true): self
	{
		$this->bindings[$abstract] = $factory;

		if ($singleton) {
			$this->singletons[$abstract] = null;
		}

		return $this;
	}

	/**
	 * Tries to execute a single method of a class. First, the class gets instantiated,
	 * then the specified method gets executed after all required method params
	 * have been built successfully.
	 */
	public function executeMethod(string $abstract, string $method): mixed
	{
		$reflection = new ReflectionClass($abstract);
		$instance = $this->createNewInstance($reflection, false);
		$deps = $this->buildDependencies($reflection, $method);

		if (!method_exists($instance, $method)) {
			throw new Exception("Method '$method' does not exists in $abstract");
		}

		return $instance->$method(...$deps);
	}

	/**
	 * Checks, if given abstract is already stored.
	 */
	private function hasBinding(string $abstract): bool
	{
		return isset($this->bindings[$abstract]);
	}

	/**
	 * Determines, if the given abstract should be resolved as a singleton.
	 */
	private function resolveBinding(string $abstract): mixed
	{
		if ($this->isSingleton($abstract)) {
			return $this->resolveSingleton($abstract);
		} else {
			return $this->executeFactory($abstract);
		}
	}

	/**
	 * Determines whether the requested abstract is
	 * cached as singleton or not.
	 */
	private function isSingleton($abstract): bool
	{
		return array_key_exists($abstract, $this->singletons);
	}

	/**
	 * Returns singleton associated with abstract. If none
	 * cached, a new instance will be constructed.
	 */
	private function resolveSingleton(string $abstract): mixed
	{
		if (!empty($this->singletons[$abstract])) {
			return $this->singletons[$abstract];
		}

		$this->singletons[$abstract] = $this->executeFactory($abstract);

		return $this->singletons[$abstract];
	}

	/**
	 * Executes the bound factory.
	 */
	private function executeFactory(string $abstract): mixed
	{
		return $this->bindings[$abstract]($this);
	}

	/**
	 * Instantiates a class by a given abstract or reflection class. If cache is true,
	 * the newly created instance will get stored in the container.
	 */
	private function createNewInstance(string|ReflectionClass $abstract, bool $cache = true): mixed
	{
		$reflection = is_string($abstract) ? new ReflectionClass($abstract) : $abstract;
		$deps = $this->buildDependencies($reflection);
		$instance = $reflection->newInstanceArgs($deps);

		if ($cache) {
			$this->bind($abstract, fn() => $instance);
		}

		return $instance;
	}

	/**
	 * Recursively tries to build all required parameters either for a
	 * classes constructor or one specific method of it.
	 */
	private function buildDependencies(ReflectionClass $reflection, string $method = ""): array
	{
		if (!empty($method)) {
			if (!$method = $reflection->getMethod($method)) {
				return [];
			};

			if (empty($params = $method->getParameters())) {
				return [];
			}
		} else {
			if (!$constructor = $reflection->getConstructor()) {
				return [];
			}

			if (empty($params = $constructor->getParameters())) {
				return [];
			}
		}

		$params = array_filter($params, function ($param) {
			return !$param->isDefaultValueAvailable();
		});

		return array_map(function ($param) {
			if (!$type = $param->getType()) {
				throw new Exception("$param has no type specified.");
			}

			return $this->resolve($type->getName());
		}, $params);
	}

	/**
	 * A quick and sloppy debugging helper.
	 */
	private function debugResolving(string $abstract)
	{
		$output = $abstract . ' ';
		$state = [];

		if ($this->hasBinding($abstract)) {
			$state[] = 'has binding';
		}

		if (!empty($state)) {
			$output .= '(' . join(', ', $state) . ')';
		} else {
			$output .= '(resolving)';
		}

		echo $output;
		echo "<br>";
	}
}
