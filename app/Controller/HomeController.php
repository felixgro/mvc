<?php

namespace App\Controller;

use App\Lib\Http\Controller;
use App\Lib\Http\Response;
use App\Lib\Support\View;

class HomeController extends Controller
{
	public static function index(): Response
	{
		return new Response(
			View::make('home')
		);
	}

	public static function about(): Response
	{
		return new Response('Hello About!');
	}
}
