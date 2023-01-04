#!/usr/bin/env php
<?php

require_once __DIR__ . "/vendor/autoload.php";

app(\Core\Database\Migration::class)->migrate();

