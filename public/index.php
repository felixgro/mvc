<?php

require_once __DIR__ . '/../vendor/autoload.php';

app(\App\Core\Http\Kernel::class)
	->handleRequest();