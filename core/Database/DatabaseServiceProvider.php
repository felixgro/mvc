<?php

namespace Core\Database;


use Core\Database\Connections\ConnectionInterface;
use Core\Database\Connections\MysqlConnection;
use Core\Providers\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{

	public function register()
	{
		$this->registerDatabase();
		$this->registerConnections();
		$this->registerMigration();
	}

	public function boot()
	{
		//
	}

	private function registerDatabase()
	{
		$this->app->singleton(Database::class, function () {
			$default = config('database.default');
			return new Database($default);
		});
	}

	private function registerConnections()
	{
		$this->app->singleton(ConnectionInterface::class, function ($c) {
			return $c->resolve(MysqlConnection::class);
		});
	}

	private function registerMigration()
	{
		if (!$this->inTerminal()) return;

		$this->app->bind(Migration::class, function ($c) {
			$migrationDir = path('database/migrations');
			return new Migration($migrationDir, $c->resolve(Database::class));
		});
	}
}