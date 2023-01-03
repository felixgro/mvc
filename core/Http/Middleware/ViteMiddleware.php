<?php

namespace Core\Http\Middleware;

use Core\Contracts\Middleware;
use Core\Http\Request;
use Core\Services\Vite;

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