<?php

namespace App\Controller;

use Core\Facades\Auth;
use Core\Facades\User;
use Core\Http\Controller;
use Core\Facades\Response;


class HomeController extends Controller
{
	public function index()
	{
		$user = User::create([
			'name' => 'admin',
			'email' => '' . rand(10000, 10000000),
			'password' => 'secret'
		]);

		dd($user);

		return view('home');
	}

	public function logout()
	{
		Auth::logout();
		return Response::redirect('http://mvc.test/');
	}

	public function about()
	{
		dd(Auth::login('me@felixgrohs.com', 'secret'));

		return json('Hello About!');
	}

	public function auth()
	{
		return json('Hello user!');
	}

	public function test()
	{
		return json('Hi');
	}
}
