<?php

namespace App\Core\Http\Middleware;

use App\Core\Contracts\Middleware;
use App\Core\Http\Request;
use App\Core\Support\Vite;

class ViteMiddleware implements Middleware
{
	/**
	 * Proxy asset requests to vite dev server if application
	 * is in development mode.
	 */
	public function __invoke(Request $request, Vite $vite)
	{
		if ($vite->devServerRunning) {
			$vite->proxyAssetRequest($request);
		}
	}
}