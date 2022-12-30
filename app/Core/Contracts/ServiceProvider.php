<?php

namespace App\Core\Contracts;

use App\Core\Container;

interface ServiceProvider
{
	public static function register(Container $c);

	public static function boot(Container $c);
}
