<?php

namespace Core\Providers;


use Core\Database\Connections\ConnectionInterface;
use Core\Database\Connections\MysqlConnection;
use Core\Database\Database;
use Core\Database\Migration;

class DatabaseServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->container->bind(Database::class, function () {
			$default = config('database.default');
			return new Database($default);
		});

		$this->container->bind(MysqlConnection::class, function () {
			$dsn = "mysql:host=localhost;user=root";
			return new MysqlConnection($dsn);
		});

		$this->container->bind(ConnectionInterface::class, function () {
			return $this->container->resolve(MysqlConnection::class);
		});

		if ($this->inTerminal()) {
			$this->container->bind(Migration::class, function () {
				return new Migration(path('database/migrations'), $this->container->resolve(Database::class));
			});
		}
	}

	public function boot()
	{
		//
	}
}