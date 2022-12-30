<?php

use App\Core\Application;
use App\Core\Http\Response;
use App\Core\Support\View;
use App\Core\Support\Config;
use App\Core\Support\Env;
use App\Core\Support\Vite;

/**
 * Main entry point when interacting with the application.
 * Returns the application singleton instance. If a class abstract
 * is given, the application tries to resolve the class internally.
 */
function app(string $abstract = ''): mixed
{
	$app = Application::getInstance();

	if (!empty($abstract)) {
		return $app->get($abstract);
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
	return array_key_exists($key, $_ENV) ? $_ENV[$key] : $default;
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

/**
 * Converts a relative file path to an absolute one on the system.
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