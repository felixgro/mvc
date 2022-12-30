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
		$user = 'admin';
		$password = 'secret';

		header('Cache-Control: no-cache, must-revalidate, max-age=0');

		$has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));

		$is_not_authenticated = (
			!$has_supplied_credentials ||
			$_SERVER['PHP_AUTH_USER'] != $user ||
			$_SERVER['PHP_AUTH_PW'] != $password
		);

		if ($is_not_authenticated) {
			header('HTTP/1.1 401 Authorization Required');
			header('WWW-Authenticate: Basic realm="Access denied"');
			exit;
		}

		return new Response("Hello $user!");
	}
}
