<?php

namespace App\Controller;

use App\Lib\Http\Controller;
use App\Lib\Http\Response;

class HomeController extends Controller
{
	public static function index(): Response
	{
		// get content
		ob_start();
		require_once path('resources/views/home.view.php');
		$slot = ob_get_clean();

		// get layout and pass content
		ob_start();
		require_once path('resources/views/layouts/app.view.php');
		$view = ob_get_clean();

		return new Response($view);
	}

	public static function about(): Response
	{
		return new Response('Hello About!');
	}
}
