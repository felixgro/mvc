<?php

namespace Core\Support;

class File
{
	/**
	 * Execute and return the execution result of a php file.
	 */
	public function require(string $path): mixed
	{
		return (require(path($path)));
	}

	/**
	 * Checks if a given file path exists on the system.
	 */
	public function exists(string $path): bool
	{
		return file_exists(path($path));
	}

	/**
	 * Checks if a given file path is missing on the system.
	 */
	public function missing(string $path): bool
	{
		return !$this->exists($path);
	}
}