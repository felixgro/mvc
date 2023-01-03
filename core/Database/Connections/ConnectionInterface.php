<?php

namespace Core\Database\Connections;

interface ConnectionInterface
{
	public function connect(array $config): void;

	public function disconnect(): void;

	public function query(string $query, array $arguments): mixed;
}