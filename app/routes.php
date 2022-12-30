<?php

/** @var App\Core\Http\Router $router */

use App\Controller\HomeController;
use App\Core\Support\Config;

$router->get('/', [HomeController::class, 'index']);

$router->get('/about', [HomeController::class, 'about']);

$router->get('/auth', [HomeController::class, 'auth']);
// ->middleware('auth')
// ->name('auth');

$router->get('/test', function (\App\Core\Support\Env $env) {
	// $val = env('APP_NAME', 'Default Name');
	// dd($env->get('APP_NAMEe', 'Default Name'));
	// return json($env);
});