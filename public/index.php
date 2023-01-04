<?php

require_once __DIR__ . '/../vendor/autoload.php';

app(\Core\Http\Kernel::class)
	->handleRequest();