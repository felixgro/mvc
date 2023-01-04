<?php

namespace Core\Facades;

use RuntimeException;

abstract class Facade
{
	/**
	 * This method needs to be defined on each and every class which extends this abstract facade.
	 * It returns the abstract used to resolve a class instance from the application service container.
	 */
	protected static function getFacadeAbstract(): string
	{
		return '';
	}

	/**
	 * Tries to get an instance of the required abstract
	 * and executes the specified method on it.
	 * This kind of acts like a static proxy to a class instance.
	 */
	public static function __callStatic(string $name, array $args): mixed
	{
		$instance = app(static::getFacadeAbstract());

		if (!method_exists($instance, $name)) {
			$abstract = $instance::class;
			throw new RuntimeException("Method '$name' is missing on $abstract");
		}

		return $instance->{$name}(...$args);
	}
}