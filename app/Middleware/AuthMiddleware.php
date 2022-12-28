<?php

namespace App\Middleware;

use App\Lib\Contracts\Middleware;
use App\Lib\Http\Request;

class AuthMiddleware implements Middleware
{
	protected string $key = 'auth';

	public static function handle(Request $request): void
	{
		//
	}
}