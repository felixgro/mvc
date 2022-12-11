<?php

namespace App\Lib\Support;

use App\Lib\Http\Response;

class View
{
	private string $layoutPath;

	public function __construct(string $layout)
	{
		$this->layoutPath = $this->getViewPath("layouts.$layout");
	}

	public function render(string $view): string
	{
		$path = $this->getViewPath($view);

		return $this->getViewContent($this->layoutPath, [
			'slot' => $this->getViewContent($path)
		]);
	}

	public static function make(string $view, string $layout = 'app'): string
	{
		return (new View($layout))->render($view);
	}

	private function getViewPath(string $key): string
	{
		$path = path('resources/views/' . str_replace('.', DIRECTORY_SEPARATOR, $key));
		$path .= '.view.php';
		return $path;
	}

	private function getViewContent(string $path, array $data = []): string
	{
		if (!empty($data)) {
			extract($data);
		}

		ob_start();
		require_once $path;
		return ob_get_clean();
	}
}