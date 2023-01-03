<?php

namespace App\Middleware;

class AuthMiddleware
{
	/**
	 * Simple example how to implement basic auth using a middleware.
	 */
	public function __invoke()
	{
		$user = 'admin';
		$password = 'secret';

		header('Cache-Control: no-cache, must-revalidate, max-age=0');

		$has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));

		$is_not_authenticated = (
			!$has_supplied_credentials ||
			$_SERVER['PHP_AUTH_USER'] != $user ||
			$_SERVER['PHP_AUTH_PW'] != $password
		);

		if ($is_not_authenticated) {
			header('WWW-Authenticate: Basic realm="Access denied"');
			return json('not authorized', 401);
		}
	}
}