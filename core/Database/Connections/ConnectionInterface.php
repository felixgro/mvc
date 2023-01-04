<?php

namespace Core\Database\Connections;

interface ConnectionInterface
{
	/**
	 * Initialize any database connection within this method.
	 * Receives the defined config values from the current connection as array.
	 * This may be used for private tokens or keys necessary for the connection.
	 */
	public function connect(array $config): void;

	/**
	 * Prevent memory leaks by providing a proper disconnect method.
	 */
	public function disconnect(): void;

	/**
	 * Execute any query on the given database connection.
	 * Query arguments always get passed as a second argument. Those
	 * arguments have to get handled in a way that prevents sql-injections.
	 */
	public function query(string $query, array $arguments): mixed;
}