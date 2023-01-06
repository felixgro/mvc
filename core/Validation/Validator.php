<?php

namespace Core\Validation;

use Somnambulist\Components\Validation\Factory;


class Validator extends Factory
{
	private ErrorBag $errorBag;

	public function __construct(ErrorBag $bag)
	{
		parent::__construct();
		$this->errorBag = $bag;
	}

	public function perform(array $data, array $rules): bool|ErrorBag
	{
		$validation = $this->make($data, $rules);
		$validation->validate();
		return $this->errorBag->parse($validation->errors());
	}
}