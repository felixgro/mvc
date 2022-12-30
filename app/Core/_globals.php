<?php

use App\Core\Application;
use App\Core\Http\Response;
use App\Core\Support\View;
use App\Core\Support\Config;
use App\Core\Support\Env;

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
	return app(Env::class)->get($key, $default);
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

// VITE DEMO
// Helpers here serve as example. Change to suit your needs.
const VITE_HOST = 'http://localhost:5134';

// For a real-world example check here:
// https://github.com/wp-bond/bond/blob/master/src/Tooling/Vite.php
// https://github.com/wp-bond/boilerplate/tree/master/app/themes/boilerplate

// you might check @vitejs/plugin-legacy if you need to support older browsers
// https://github.com/vitejs/vite/tree/main/packages/plugin-legacy

// Prints all the html entries needed for Vite

function vite(string $entry): string
{
	return "\n" . jsTag($entry)
		. "\n" . jsPreloadImports($entry)
		. "\n" . cssTag($entry);
}


// Some dev/prod mechanism would exist in your project
function isDev(string $entry): bool
{
	// This method is very useful for the local server
	// if we try to access it, and by any means, didn't started Vite yet
	// it will fallback to load the production files from manifest
	// so you still navigate your site as you intended!

	static $exists = null;
	if ($exists !== null) {
		return $exists;
	}
	$handle = curl_init(VITE_HOST . '/' . $entry);
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($handle, CURLOPT_NOBODY, true);

	curl_exec($handle);
	$error = curl_errno($handle);
	curl_close($handle);

	return $exists = !$error;
}


// Helpers to print tags
function jsTag(string $entry): string
{
	$url = isDev($entry)
		? VITE_HOST . '/' . $entry
		: assetUrl($entry);

	if (!$url) {
		return '';
	}
	return '<script type="module" crossorigin src="'
		. $url
		. '"></script>';
}

function jsPreloadImports(string $entry): string
{
	if (isDev($entry)) {
		return '';
	}

	$res = '';
	foreach (importsUrls($entry) as $url) {
		$res .= '<link rel="modulepreload" href="'
			. $url
			. '">';
	}
	return $res;
}

function cssTag(string $entry): string
{
	// not needed on dev, it's inject by Vite
	if (isDev($entry)) {
		return '';
	}

	$tags = '';
	foreach (cssUrls($entry) as $url) {
		$tags .= '<link rel="stylesheet" href="'
			. $url
			. '">';
	}
	return $tags;
}


// Helpers to locate files

function getManifest(): array
{
	$content = file_get_contents(path('public/build/manifest.json'));
	return json_decode($content, true);
}

function assetUrl(string $entry): string
{
	$manifest = getManifest();

	return isset($manifest[$entry])
		? '/build/' . $manifest[$entry]['file']
		: '';
}

function importsUrls(string $entry): array
{
	$urls = [];
	$manifest = getManifest();

	if (!empty($manifest[$entry]['imports'])) {
		foreach ($manifest[$entry]['imports'] as $imports) {
			$urls[] = '/build/' . $manifest[$imports]['file'];
		}
	}
	return $urls;
}

function cssUrls(string $entry): array
{
	$urls = [];
	$manifest = getManifest();

	if (!empty($manifest[$entry]['css'])) {
		foreach ($manifest[$entry]['css'] as $file) {
			$urls[] = '/build/' . $file;
		}
	}
	return $urls;
}

function viteServerRunning(): bool
{
	$curl = curl_init(VITE_HOST);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	$res = curl_exec($curl);
	curl_close($curl);

	return $res !== false;
}
