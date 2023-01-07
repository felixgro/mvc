<?php

namespace Core\Database;

class Migration
{
	public array $migrationFiles = [];
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

	public function migrate(): int
	{
		foreach ($this->migrationFiles as $file) {
			if ($this->executeSQLFile($file) === 0) {
				return 0;
			};
		}

		return 1;
	}

	public function executeSQLFile($file): int
	{
		if (!file_exists($file)) {
			return 0;
		}

		$queries = explode(';', file_get_contents($file));
		$queries = array_filter($queries, fn($q) => !empty($q));

		foreach ($queries as $query) {
			$this->executeQuery($query);
		}

		return 1;
	}

	private function executeQuery(string $query)
	{
		$this->db->query($query);
	}
}