<?php

namespace App\Lib\Core;

use Exception;
use ReflectionClass;

class Container
{
	private array $bindings = [];
	private array $singletons = [];

	public function resolve(string $abstract): mixed
	{
		// $this->debugResolving($abstract);
		if ($this->hasBinding($abstract)) {
			return $this->resolveBinding($abstract);
		}

		return $this->createNewInstance($abstract);
	}

	public function bind(string $abstract, callable $factory, bool $singleton = true): self
	{
		$this->bindings[$abstract] = $factory;

		if ($singleton) {
			$this->singletons[$abstract] = null;
		}

		return $this;
	}

	private function hasBinding(string $abstract): bool
	{
		return isset($this->bindings[$abstract]);
	}

	private function resolveBinding(string $abstract): mixed
	{
		if ($this->isSingleton($abstract)) {
			return $this->resolveSingleton($abstract);
		} else {
			return $this->executeFactory($abstract);
		}
	}

	private function isSingleton($abstract): bool
	{
		return array_key_exists($abstract, $this->singletons);
	}

	private function resolveSingleton(string $abstract): mixed
	{
		if (!empty($this->singletons[$abstract])) {
			return $this->singletons[$abstract];
		}

		$this->singletons[$abstract] = $this->executeFactory($abstract);
		return $this->singletons[$abstract];
	}

	private function executeFactory(string $abstract): mixed
	{
		return $this->bindings[$abstract]($this);
	}

	private function createNewInstance(string $abstract): mixed
	{
		$reflection = new ReflectionClass($abstract);

		$deps = $this->buildDependencies($reflection);
		$instance = $reflection->newInstanceArgs($deps);

		$this->bind($abstract, fn() => $instance);

		return $instance;
	}

	private function buildDependencies(ReflectionClass $reflection): array
	{
		if (!$constructor = $reflection->getConstructor()) {
			return [];
		}

		if (empty($params = $constructor->getParameters())) {
			return [];
		}

		$params = array_filter($params, function ($param) {
			return !$param->isDefaultValueAvailable();
		});

		return array_map(function ($param) {
			if (!$type = $param->getType()) {
				throw new Exception();
			}

			return $this->resolve($type->getName());
		}, $params);
	}

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
