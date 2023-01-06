<?php

namespace App\Controller;

use Core\Facades\Auth;
use Core\Http\Controller;
use Core\Facades\Response;
use Core\Http\Request;


class AuthController extends Controller
{
	public function register(Request $request)
	{

	}

	public function login(Request $request)
	{
		$data = $request->only(['email', 'password', 'remember']);

		$errors = $this->validate($data, [
			'email' => 'required|email',
			'password' => 'required|string',
			'remember' => 'string'
		]);

		if ($errors->isEmpty()) {
			if (Auth::login($data['email'], $data['password'], isset($data['remember']))) {
				return Response::redirect('http://mvc.test'); // Successful login
			};

			$errors->addGlobal('Wrong credentials');
		}

		// Return all errors as json array
		return $errors->jsonResponse();
	}

	public function logout(Response $response)
	{
		Auth::logout();
		return Response::redirect('http://mvc.test/');
	}
}
