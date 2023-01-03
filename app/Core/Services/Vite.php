<?php

namespace App\Core\Services;

use App\Core\Http\Request;
use function App\Core\Support\str_starts_with;

class Vite
{
	/**
	 * Weather or not vite's dev server is currently running and available.
	 */
	public bool $devServerRunning = false;

	/**
	 * Host path of vite dev server.
	 * The string contain the protocol, domain and port.
	 */
	private string $host;

	/**
	 * Contains entry data, defined in build manifest file.
	 */
	private array $manifest;

	/**
	 * Checks, if dev server is available only if application is
	 * in development mode.
	 */
	public function __construct(string $host, string $manifestPath)
	{
		$this->host = $host;
		$this->manifest = $this->getManifest($manifestPath);

		if (config('app.env') === 'development') {
			$this->devServerRunning = $this->isDevServerAvailable();
		}
	}

	/**
	 * Main entry point for generating assets, defined in vite.config.js
	 * This function generates html-ready markup which imports required
	 * dependencies as fast as possible.
	 */
	public function asset(string $entry): string
	{
		return $this->jsTag($entry)
			. "\n" . $this->jsPreloadImports($entry)
			. "\n" . $this->cssTag($entry);
	}

	/**
	 * Redirects static assets to vite host if
	 * dev server is available.
	 */
	public function proxyAssetRequest(Request $request): void
	{
		$path = $request->getPathInfo();

		if (str_starts_with($path, '/assets')) {
			header("Location:" . $this->host . $path);
			exit;
		}
	}

	/**
	 * Get js tag of specified entry.
	 */
	private function jsTag(string $entry): string
	{
		$url = $this->devServerRunning
			? $this->host . '/' . $entry
			: $this->assetUrl($entry);

		if (!$url) {
			return '';
		}

		return '<script type="module" crossorigin src="'
			. $url
			. '"></script>';
	}

	/**
	 * Generate required preload tags of specified entry.
	 * This gets handled internally by vite's dev server.
	 */
	private function jsPreloadImports(string $entry): string
	{
		if ($this->devServerRunning) {
			return '';
		}

		$res = '';
		foreach ($this->importUrls($entry) as $url) {
			$res .= '<link rel="modulepreload" href="'
				. $url
				. '">';
		}
		return $res;
	}

	/**
	 * Generate required css tags of specified entry.
	 * This gets injected automatically by vite's dev server.
	 */
	private function cssTag(string $entry): string
	{
		if ($this->devServerRunning) {
			return '';
		}

		$tags = '';
		foreach ($this->cssUrls($entry) as $url) {
			$tags .= '<link rel="stylesheet" href="'
				. $url
				. '">';
		}
		return $tags;
	}

	/**
	 * Determine an entry's asset url by checking the build manifest.
	 */
	private function assetUrl(string $entry): string
	{
		return isset($this->manifest[$entry])
			? '/build/' . $this->manifest[$entry]['file']
			: '';
	}

	/**
	 * Determine all required import urls by checking the build manifest.
	 */
	private function importUrls(string $entry): array
	{
		$urls = [];

		if (!empty($this->manifest[$entry]['imports'])) {
			foreach ($this->manifest[$entry]['imports'] as $imports) {
				$urls[] = '/build/' . $this->manifest[$imports]['file'];
			}
		}

		return $urls;
	}

	/**
	 * Determine all required css files by checking the build manifest.
	 */
	private function cssUrls(string $entry): array
	{
		$urls = [];

		if (!empty($this->manifest[$entry]['css'])) {
			foreach ($this->manifest[$entry]['css'] as $file) {
				$urls[] = '/build/' . $file;
			}
		}
		return $urls;
	}

	/**
	 * Read vite's build manifest, which stores all public build files along
	 * with the correlating version hash.
	 */
	private function getManifest($path): array
	{
		$content = file_get_contents($path);
		return json_decode($content, true);
	}

	/**
	 * Checks if dev server is available by sending a
	 * request to the specified vite host
	 */
	private function isDevServerAvailable(): bool
	{
		$curl = curl_init($this->host);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$res = curl_exec($curl);
		curl_close($curl);

		return $res !== false;
	}
}