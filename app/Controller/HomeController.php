<?php

namespace App\Controller;

use Core\Http\Controller;
use Core\Http\Request;
use Core\Http\Response;


class HomeController extends Controller
{
	public function index(Request $request): Response
	{
		return view('app:home');
	}

	public function about(): Response
	{
		return json('Hello About!');
	}

	public function auth(): Response
	{
		return json('Hello user!');
	}

	public function test(): Response
	{
		return json('Hi');
	}
}
