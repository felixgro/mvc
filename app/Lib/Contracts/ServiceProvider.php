<?php

namespace App\Lib\Contracts;

use App\Lib\Core\Container;

interface ServiceProvider
{
   public static function register(Container $c);
   public static function boot(Container $c);
}
