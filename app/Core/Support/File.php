<?php

namespace App\Core\Support;

class File
{
	/**
	 * Execute and return the execution result of a php file.
	 */
	public static function require(string $path): mixed
	{
		return (require(path($path)));
	}

	/**
	 * Checks if a given file path exists on the system.
	 */
	public static function exists(string $path): bool
	{
		return file_exists(path($path));
	}

	/**
	 * Checks if a given file path is missing on the system.
	 */
	public static function missing(string $path): bool
	{
		return !self::exists($path);
	}
}