<?php

use Core\Application;
use Core\Http\Router;
use Core\Support\Config;
use Core\Support\Env;
use Core\Facades\Response;
use Core\Facades\View;
use Core\Support\Vite;

/**
 * Main entry point when interacting with the application.
 * Returns the application singleton instance. If a class abstract
 * is given, the application tries to resolve the class internally.
 */
function app(string $abstract = ''): mixed
{
	$app = Application::getInstance();

	if (empty($abstract)) {
		return $app;
	}

	return $app->resolve($abstract);
}

/**
 * Main entry point for getting application config data.
 * Config name can either be the name of the config file (without .php extension) or
 * a specific config value using dot notation:
 * "app.name" => name value in app.php config file
 */
function config(string $name, mixed $default = null): mixed
{
	return app(Config::class)->get($name, $default);
}

/**
 * Main entry point for getting application environment data.
 * Returns specified default if no value found.
 */
function env(string $key, mixed $default = null): mixed
{
	return app(Env::class)->get($key, $default);
}

/**
 * Returns a response, which contains the rendered view as a string.
 */
function view(string $name, array $data = []): \Core\Http\Response
{
	return Response::setContent(
		View::make($name)->render($data)
	);
}

/**
 * Returns a json encoded response with the
 * specified data and status.
 */
function json(mixed $data, int $status = 200): \Core\Http\Response
{
	return Response::setStatusCode($status)
		->setContent(json_encode($data));
}

/**
 * Stops any execution with specified status message and code.
 */
function abort(int $status, $message = null)
{
	http_response_code($status);

	if (isset($message)) {
		echo json_encode(['message' => $message]);
	}

	exit(0);
}

/**
 * Makes sure the path starts and ends with a slash.
 * This is used to synchronize route definitions with
 * the correlating route action keys in storage.
 */
function sanitizeUriPath(string $path): string
{
	if (!str_starts_with($path, '/')) {
		$path = '/' . $path;
	}

	return rtrim($path, '/');
}

function route(string $name): string
{
	$route = app(Router::class)->getRoute($name);
	return $route->path;
}

/**
 * Converts a file path from project root to an absolute one on the system.
 * By default, this function generates all directories, which are missing.
 */
function path(string ...$path): string
{
	if (is_array($path)) {
		// remove any leading/trailing slashes
		array_map(fn($p) => ltrim(rtrim($p, '/'), '/'), $path);
		// merge paths into a single string
		$path = implode('/', $path);
	}

	// goto upper root directory if called within http request execution
	if (php_sapi_name() !== 'cli' && !str_starts_with($path, "../")) {
		$path = "../" . $path;
	}

	// trim any leading slash
	$path = ltrim($path, '/');

	// replace all slashes with specified directory separator
	return str_replace('/', DIRECTORY_SEPARATOR, $path);
}
