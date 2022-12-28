<?php

namespace App\Lib\Http;

class Route
{
	private array $middlewares = [];
	private string $alias;

	public function __construct()
	{
		
	}

	public function middleware(string $key): self
	{
		return $this;
	}

	public function name(string $name): self
	{
		$this->alias = $name;
		return $this;
	}

	public function isAlias(string $alias): bool
	{
		return $this->alias === $alias;
	}
}