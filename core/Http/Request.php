<?php

namespace Core\Http;

use Symfony\Component\HttpFoundation\Request as RequestFoundation;

class Request extends RequestFoundation
{
	public function getSanitizedPath(): string
	{
		return rtrim($this->getPathInfo(), '/');
	}
}
