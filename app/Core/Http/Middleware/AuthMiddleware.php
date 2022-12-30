<?php

namespace App\Core\Http\Middleware;

use App\Core\Contracts\Middleware;
use App\Core\Http\Request;
use App\Core\Http\Response;

class AuthMiddleware implements Middleware
{
	public function __invoke(Request $request)
	{
		// return json('No Access', 403);
	}
}