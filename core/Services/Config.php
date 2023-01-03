<?php

namespace Core\Services;

use Core\Support\File;
use Exception;

class Config
{
	/**
	 * Path to directory, which contains all config files.
	 */
	private string $configDir;

	/**
	 * Cache for efficient reuse of config files.
	 */
	private array $cache = [];

	/**
	 * Instantiating requires a path to the config directory.
	 */
	public function __construct(string $configDir)
	{
		$this->configDir = $configDir;
	}

	/**
	 * Main entry point for getting application config data.
	 * Config id can either be the name of the config file (without .php extension) or
	 * a specific config value using dot notation:
	 * "app.name" => name value in app.php config file
	 */
	public function get(string $id, mixed $default = null): mixed
	{
		[$configKey, $valueKey] = explode('.', $id);
		$configData = $this->getConfigData($configKey);

		if (isset($valueKey) && !array_key_exists($valueKey, $configData)) {
			return $default;
		}

		$value = isset($valueKey) ? $configData[$valueKey] : $configData;

		return $value ?? $default;
	}

	/**
	 * Retrieves a config array from the application. Either
	 * from cache or fresh if not already set.
	 */
	private function getConfigData(string $key): array
	{
		if (array_key_exists($key, $this->cache)) {
			return $this->cache[$key];
		}

		$configFile = $this->configDir . $key . '.php';

		if (File::missing($configFile)) {
			throw new Exception("Could not find config file '$configFile'");
		}

		$this->cache[$key] = File::require($configFile);;

		return $this->cache[$key];
	}
}