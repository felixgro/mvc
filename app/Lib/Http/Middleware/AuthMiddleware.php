<?php

namespace App\Lib\Http\Middleware;

use App\Lib\Contracts\Middleware;
use App\Lib\Http\Request;
use App\Lib\Http\Response;

class AuthMiddleware implements Middleware
{
	public static function handle(Request $request): void
	{
		// dd('Hello World!');
	}

	public function __invoke(Request $request)
	{
		return json('No Access', 403);
	}
}