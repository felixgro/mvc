<?php

namespace Core\Database\Connections;

use PDO;

class MysqlConnection implements ConnectionInterface
{
	/**
	 * Store the active mysql connection as a PDO instance.
	 **/
	private PDO $pdo;

	/**
	 * Connect using application configuration values from database.php
	 */
	public function connect(array $config): void
	{
		$dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";

		$this->pdo = new PDO($dsn, $config['username'], $config['password'], [
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
		]);
	}

	/**
	 * Destroying the reference to the pdo instance leads to the connection
	 * getting disconnected.
	 */
	public function disconnect(): void
	{
		unset($this->pdo);
	}

	/**
	 * Perform any mysql query using the active pdo instance.
	 */
	public function query(string $query, array $arguments): array|bool
	{
		$statement = $this->pdo->prepare($query);
		$statement->execute($arguments);
		return $statement->fetchAll();
	}
}