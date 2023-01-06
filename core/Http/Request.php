<?php

namespace Core\Http;

use Symfony\Component\HttpFoundation\Request as RequestFoundation;

class Request extends RequestFoundation
{
	public function only(array $params): array
	{
		$values = [];
		foreach ($params as $param) {
			$values[$param] = $this->get($param);
		}
		return $values;
	}

	public function getSanitizedPath(): string
	{
		return rtrim($this->getPathInfo(), '/');
	}
}
