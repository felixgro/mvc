<?php

const TERMINAL_EXECUTION = 0;

require_once __DIR__ . '/../vendor/autoload.php';

app(\Core\Http\Kernel::class)
	->handleRequest();