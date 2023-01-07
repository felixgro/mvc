<?php

namespace Core\Database;

use Core\Database\Connections\ConnectionInterface;
use Exception;


class Database
{
	/**
	 * Stores the key of specified default connection.
	 */
	private string $defaultConnection = '';

	/**
	 * Temporarily store a connection key only for the following query.
	 */
	private string $activeConnection = '';

	/**
	 * Collection of all active connection associated
	 * with the correlating keys.
	 */
	private array $connectionPool = [];

	/**
	 * Needs the key of a specified default connection.
	 */
	public function __construct(string $default)
	{
		$this->defaultConnection = $default;
	}

	/**
	 * Tries to establish a connection using the specified connection key & handler.
	 */
	public function connect(string $connection = ''): ConnectionInterface
	{
		$this->activeConnection = $this->getConnectionKey($connection);

		$config = config('database.connections')[$this->activeConnection];
		$ConnectionHandler = $config['handler'];

		try {
			$this->connectionPool[$connection] = new $ConnectionHandler();
			$this->connectionPool[$connection]->connect($config);
			return $this->connectionPool[$connection];
		} catch (\Throwable $error) {
			throw new Exception(
				"Could not establish database connection (using $ConnectionHandler)",
				0,
				$error
			);
		}
	}

	/**
	 * Tries to disconnect from an active connection. Does nothing and
	 * fails silently if no connection found.
	 */
	public function disconnect(string $connection = ''): void
	{
		$connection = $this->getConnectionKey($connection);

		if (array_key_exists($connection, $this->connectionPool)) {
			$this->connectionPool[$connection]->disconnect();
			unset($this->connectionPool[$connection]);
		}
	}

	/**
	 * Proxies a query to the current connection.
	 */
	public function query(string $query, array $arguments = [], array $options = []): mixed
	{
		$connection = $this->getCurrentConnection();

		try {
			return $connection->query($query, $arguments, $options);
		} catch (\Throwable $error) {
			$connectionKey = $this->getConnectionKey();
			throw new Exception("Could not perform query using connection $connectionKey", 0, $error);
		}
	}

	/**
	 * Tries to find the current active connection. If none found the default
	 * connection gets returned. If no connection available, an exception gets thrown.
	 */
	private function getCurrentConnection(): ConnectionInterface
	{
		if (!empty($this->activeConnection) && array_key_exists($this->activeConnection, $this->connectionPool)) {
			$connection = $this->connectionPool[$this->activeConnection];
			$this->activeConnection = '';
		}

		if (array_key_exists($this->defaultConnection, $this->connectionPool)) {
			$connection = $this->connectionPool[$this->defaultConnection];
		}

		if (!isset($connection)) {
			$connection = $this->connect($this->defaultConnection);
		}

		if (!isset($connection)) {
			throw new \Exception('No connection available');
		}

		return $connection;
	}

	/**
	 * Returns the current connection key.
	 */
	private function getConnectionKey(string $connection = ''): string
	{
		return empty($connection) ? $this->defaultConnection : $connection;
	}
}