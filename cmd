#!/usr/bin/env php
<?php


const TERMINAL_EXECUTION = true;

require_once __DIR__ . "/vendor/autoload.php";

app(\Core\Database\Migration::class)->migrate();

