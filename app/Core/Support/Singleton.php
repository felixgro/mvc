<?php

namespace App\Core\Support;

/**
 * Extend this class for an enhanced singleton.
 */
class Singleton
{
	/**
	 * The actual singleton's instance almost always resides inside a static
	 * field. In this case, the static field is an array, where each subclass of
	 * the Singleton stores its own instance.
	 */
	private static $instances = [];

	private static $constructing = false;

	/**
	 * Cloning and unserialization are not permitted for singletons.
	 */
	protected function __clone()
	{
		//
	}

	public function __wakeup()
	{
		throw new \Exception("Cannot unserialize singleton");
	}

	protected function __constructed()
	{
		//
	}

	/**
	 * The method you use to get the Singleton's instance.
	 */
	public static function getInstance()
	{
		$subclass = static::class;

		if (!isset(self::$instances[$subclass])) {
			self::$constructing = true;
			// Note that here we use the "static" keyword instead of the actual
			// class name. In this context, the "static" keyword means "the name
			// of the current class". That detail is important because when the
			// method is called on the subclass, we want an instance of that
			// subclass to be created here.

			self::$instances[$subclass] = new $subclass();
			self::$instances[$subclass]->__constructed();
		}

		return self::$instances[$subclass];
	}
}