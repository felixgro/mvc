<?php

return [
	'default' => 'mysql',

	'timestamps' => 'Y-m-d H:i:s',

	'connections' => [
		'sqlite' => [
			'url' => env('DATABASE_URL'),
			'database' => env('DB_DATABASE', ''),
			'prefix' => env('DB_PREFIX', ''),
			'handler' => Core\Database\Connections\SqliteConnection::class,
		],

		'mysql' => [
			'url' => env('DB_URL', ''),
			'host' => env('DB_HOST', '127.0.0.1'),
			'port' => env('DB_PORT', '3306'),
			'database' => env('DB_DATABASE', 'mvc'),
			'username' => env('DB_USERNAME', 'root'),
			'password' => env('DB_PASSWORD', ''),
			'unix_socket' => env('DB_SOCKET', ''),
			'prefix' => env('DB_PREFIX', ''),
			'charset' => 'utf8mb4',
			'collation' => 'utf8mb4_unicode_ci',
			'engine' => 'INNO_DB',
			'handler' => Core\Database\Connections\MysqlConnection::class
		]
	]
];