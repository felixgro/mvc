<?php

namespace Core\Http;

use Symfony\Component\HttpFoundation\Response as ResponseFoundation;


class Response extends ResponseFoundation
{
	public function redirect(string $to): self
	{
		header('Location: ' . $to);
		return $this;
	}
}