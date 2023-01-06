<?php

namespace Core\Http;

use Core\Validation\Validator;

abstract class Controller
{
	private Validator $validator;

	public function __construct(Validator $validator)
	{
		$this->validator = $validator;
	}

	protected function validate(array $data, array $rules)
	{
		return $this->validator->perform($data, $rules);
	}
}
