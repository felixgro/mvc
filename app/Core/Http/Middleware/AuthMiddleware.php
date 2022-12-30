<?php

namespace App\Core\Http\Middleware;

use App\Core\Contracts\Middleware;
use App\Core\Http\Request;

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