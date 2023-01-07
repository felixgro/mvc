<?php

namespace Core\Console\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Core\Database\Migration;


class MigrateCommand extends Command
{
	protected static $defaultName = 'migrate';

	protected static $defaultDescription = 'Migrates the database tables.';

	private Migration $migrator;

	protected function configure()
	{
		$this->migrator = app(Migration::class);
		$this->addArgument('file', InputArgument::OPTIONAL, 'Name of a single sql file to execute.');
	}

	public function __invoke(InputInterface $input, OutputInterface $output): int
	{
		$fileArg = $input->getArgument('file');

		if (isset($fileArg)) {
			$pathToSQL = "database/migrations/$fileArg.sql";
			$file = path($pathToSQL);
			$res = $this->migrator->executeSQLFile($file);

			if ($res === 1) {
				$output->writeln("Migrated '$pathToSQL'");
			} else {
				$output->writeln("Could not find migration file ($pathToSQL)");
			}

			return $res;
		} else {
			$res = $this->migrator->migrate();

			if ($res === 1) {
				foreach ($this->migrator->migrationFiles as $file) {
					$output->writeln("Migrated '$file'");
				}
			} else {
				$output->writeln("Error while global migration.");
			}

			return $res;
		}
	}
}