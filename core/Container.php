<?php

namespace Core;

use ReflectionClass;
use ReflectionFunction;
use ReflectionParameter;
use ReflectionType;
use Exception;
use Throwable;

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
		return $this;
	}

	/**
	 * Registers a new factory for a singleton instance in the container.
	 * If no factory set, the container tries to instantiate the given abstract.
	 */
	public function singleton(string $abstract, callable $factory = null): self
	{
		if (is_null($factory)) {
			$factory = function () use ($abstract) {
				return $this->createNewInstance($abstract);
			};
		}

		$this->singletons[$abstract] = null;
		return $this->bind($abstract, $factory);
	}

	/**
	 * Tries to execute a single method of a class. First, the class gets instantiated,
	 * then the specified method gets executed after all required method params
	 * have been built successfully.
	 */
	public function executeMethod(mixed $action): mixed
	{
		if ($this->isCallbackAction($action)) {
			return $this->executeCallback($action);
		}

		[$abstract, $method] = $action;

		$instance = $this->getInstance($abstract, $reflection);
		$deps = $this->buildDependencies($reflection, $method);

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
	 * Executes a callback and tries to inject all required dependencies.
	 */
	private function executeCallback(callable $action): mixed
	{
		$function = new ReflectionFunction($action);
		$args = $this->buildDependencies($function);
		return $action(...$args);
	}

	/**
	 * Generates a reflection class from the giving abstract. If abstract is of type
	 * string, a fresh instance will be created.
	 */
	private function getInstance(object|string $abstract, &$reflection): mixed
	{
		$reflection = new ReflectionClass($abstract);
		return is_object($abstract) ? $abstract : $this->createNewInstance($reflection);
	}

	/**
	 * Instantiates a class by a given abstract or reflection class. If cache is true,
	 * the newly created instance will get stored in the container.
	 */
	private function createNewInstance(string|ReflectionClass $abstract, bool $cache = false): mixed
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
	private function buildDependencies(ReflectionClass|ReflectionFunction $reflection, string $method = ""): array
	{
		$params = $this->resolveReflectionParameters($reflection, $method);

		$params = array_filter($params, function ($param) {
			return !$param->isDefaultValueAvailable();
		});

		return array_map(function ($param) {
			$type = $this->getParameterType($param);

			if ($this->isNativeType($type)) {
				throw new Exception("Cannot resolve parameter $param using native type annotation ($type)");
			}

			return $this->resolve($type->getName());
		}, $params);
	}

	/**
	 * Returns an array which contains the necessary parameters of a specified reflection class or function.
	 * If this method receives a reflection class without any specific method name the constructors parameters will get returned.
	 */
	private function resolveReflectionParameters(ReflectionClass|ReflectionFunction $reflection, string $method = ''): array
	{
		if (!empty($method)) {
			if (!$method = $reflection->getMethod($method)) {
				return [];
			}
			return $method->getParameters();
		} elseif ($reflection instanceof ReflectionFunction) {
			return $reflection->getParameters();
		}

		// Fallback to constructor
		if (!$constructor = $reflection->getConstructor()) {
			return [];
		}

		return $constructor->getParameters();
	}

	/**
	 * Returns the reflection type of a given parameter.
	 * If no type specified, an exception is thrown.
	 */
	private function getParameterType(ReflectionParameter $param): ReflectionType
	{
		if (!$type = $param->getType()) {
			throw new Exception("Parameter $param has no type specified.");
		}

		return $type;
	}

	/**
	 * Checks if a provided type string is a native type.
	 * php docs regarding floats: for historical reasons "double" is returned in case of a float, and not simply "float"
	 */
	private function isNativeType(string $type): bool
	{
		return in_array($type, [
			"boolean",
			"integer",
			"double",
			"string",
			"array",
			"object",
			"resource",
			"resource (closed)",
			"NULL",
			"unknown type"
		]);
	}

	/**
	 * Determines if the provided action is a single callback functions.
	 */
	private function isCallbackAction(mixed $action): bool
	{
		return !is_array($action) && is_callable($action);
	}
}
