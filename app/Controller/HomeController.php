<?php

namespace App\Controller;

use App\Core\Http\Controller;
use App\Core\Http\Request;
use App\Core\Http\Response;
use function json;
use function view;


class HomeController extends Controller
{
	public function index(Request $request): Response
	{
		/*
		return view('app:home')->with([
			'user' => $user
		]);
		*/
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
}
