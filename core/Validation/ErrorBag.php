<?php

namespace Core\Validation;

use Core\Facades\Response;
use Somnambulist\Components\Validation\ErrorBag as BaseBag;
use Somnambulist\Components\Validation\ErrorMessage;

class ErrorBag
{
	private BaseBag $bag;

	public function parse(BaseBag $bag): self
	{
		$this->bag = $bag;
		return $this;
	}

	public function addGlobal(string $message)
	{
		$this->add('global', $message);
	}

	public function add(string $key, string $message = '')
	{
		$msg = new ErrorMessage($key, []);
		$msg->setMessage($message);
		$this->bag->add($key, '', $msg);
	}

	public function isEmpty(): bool
	{
		return $this->bag->count() === 0;
	}

	public function toArray(): array
	{
		$errors = [];
		$bag = $this->bag->toDataBag();
		foreach ($bag->all() as $key => $error) {
			$errors[$key] = $error[array_key_first($error)]->toString();
		}
		return $errors;
	}

	public function jsonResponse()
	{
		return Response::setContent(json_encode($this->toArray()));
	}
}