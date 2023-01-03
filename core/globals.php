<?php

use Core\Application;
use Core\Http\Response;
use Core\Http\Router;
use Core\Services\Config;
use Core\Services\Env;
use Core\Services\View;
use Core\Services\Vite;

/**
 * Main entry point when interacting with the application.
 * Returns the application singleton instance. If a class abstract
 * is given, the application tries to resolve the class internally.
 */
function app(string $abstract = ''): mixed
{
	$app = Application::getInstance();

	if (!empty($abstract)) {
		return $app->resolve($abstract);
	}

	return $app;
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
	return Env::getInstance()->get($key, $default);
}

/**
 * Returns a response, which contains the rendered view as a string
 * with the specified status code. The name of the view has to
 * consists of 2 parts seperated by a colon (f.e: "app:home")
 */
function view(string $name, int $status = 200): Response
{
	return new Response(
		View::make($name),
		$status
	);
}

/**
 * Returns a json encoded response with the
 * specified data and status.
 */
function json(mixed $data, int $status = 200): Response
{
	return new Response(
		json_encode($data),
		$status
	);
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

	die();
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

/**
 * Generates asset tags for scripts, styles and images handled
 * by vite. This utility helper works both in production and
 * in development on vite's dev server.
 */
function vite(string $entry): string
{
	return app(Vite::class)->asset($entry);
}

function array_select(array $source, int $dimension, $value)
{
	// TODO: before starting recursion, make sure that $dimension >=1
	// and the actual depth of $source is > $dimension

	if ($dimension === 1) {
		// return the value at that depth, empty if not set
		return $source[$value] ?? [];
	} else {

		foreach ($source as $index => $subset) {
			$source[$index] = array_select($subset, $dimension - 1, $value);
		}

		return $source;
	}
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
function path(string $path, bool $generateDirs = true): string
{
	if (!str_starts_with($path, "../")) $path = "../" . $path;

	if (str_starts_with($path, '/')) $path = ltrim($path, '/');
	$path = str_replace('/', DIRECTORY_SEPARATOR, $path);
	$dirName = pathinfo($path, PATHINFO_DIRNAME);

	if ($generateDirs && !file_exists($dirName)) {
		mkdir($dirName, 0777, true);
	}

	return $path;
}