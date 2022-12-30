<?php

namespace App\Core\Support;

use Dotenv\Dotenv;

class Env
{
	/**
	 * Reference to dotenv instance.
	 */
	private Dotenv $dotenv;

	/*
	 * Initialize dotenv library, which loads all environment variables
	 * defined in .env file in the $_ENV super global variable.
	 */
	public function __construct()
	{
		$this->dotenv = Dotenv::createImmutable(path('./'));
		$this->dotenv->load();
	}

	/**
	 * Main entry point for getting application environment data.
	 * Returns specified default if no value found.
	 */
	public function get(string $key, mixed $default = null): mixed
	{
		return array_key_exists($key, $_ENV) ? $_ENV[$key] : $default;
	}
}