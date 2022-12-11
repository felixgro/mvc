<?php

namespace App\Middleware;

use App\Lib\Contracts\Middleware;
use App\Lib\Http\Request;

class AppMiddleware implements Middleware
{
	public static function handle(Request $request): void
	{
		//
	}
}