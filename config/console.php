<?php

return [
	'enable' => true,

	'commands' => [
		Core\Console\Commands\HelloWorldCommand::class,
		Core\Console\Commands\MigrateCommand::class
	]
];