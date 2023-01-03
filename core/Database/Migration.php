<?php

namespace Core\Database;

class Migration
{
	private array $migrationFiles = [];
	private Database $db;

	public function __construct(string $migrationDir, Database $db)
	{
		$this->db = $db;
		$this->loadMigrations($migrationDir);
	}

	private function loadMigrations(string $dir)
	{
		$this->migrationFiles = glob($dir . '/*');
	}

	public function migrate()
	{
		foreach ($this->migrationFiles as $file) {
			$this->executeSQLFile($file);
		}
	}

	private function executeSQLFile($file)
	{
		if (!file_exists($file)) {
			throw new \Exception("Cannot find migration file $file");
		}

		$queries = explode(';', file_get_contents($file));
		$queries = array_filter($queries, fn($q) => !empty($q));

		foreach ($queries as $query) {
			$this->executeQuery($query);
		}
	}

	private function executeQuery(string $query)
	{
		$this->db->query($query);
	}
}