<?php

namespace App\Core\Http\Middleware;

use App\Core\Contracts\Middleware;
use App\Core\Http\Request;

class ViteMiddleware implements Middleware
{
	public function __invoke(Request $request)
	{
		if (!viteServerRunning()) return;

		$path = $request->getPathInfo();

		if (str_starts_with($path, '/assets')) {
			header("Location: http://localhost:5134$path");
			exit;
		}
	}
}