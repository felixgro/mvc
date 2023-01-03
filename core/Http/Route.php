<?php

namespace Core\Http;

class Route
{
	public string $path;
	public string $alias;
	public array $middlewares = [];

	public function __construct(string $path)
	{
		$this->path = sanitizeUriPath($path);
	}

	public function middleware(string $key): self
	{
		$this->middlewares[] = $key;
		return $this;
	}

	public function name(string $name): self
	{
		$this->alias = $name;
		return $this;
	}
}