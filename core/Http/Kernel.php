<?php

namespace Core\Http;

use Symfony\Component\HttpKernel\HttpKernel;

class Kernel extends HttpKernel
{
	/**
	 * Main entry point for the incoming http request.
	 * This should only get called once within the root php file.
	 */
	public function handleRequest()
	{
		$request = app(Request::class);

		try {
			$response = $this->handle($request);
			$response->send();
			$this->terminate($request, $response);
		} catch (\Throwable $exception) {
			dump('exception in http kernel:');
			dd($exception);
		}
	}
}