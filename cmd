#!/usr/bin/env php
<?php

require_once __DIR__ . "/vendor/autoload.php";

if (!config('console.enable'))
	exit(1);

app(\Core\Console\Kernel::class)
	->handleExecution();



