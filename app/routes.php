<?php

/** @var App\Core\Http\Router $router */

use App\Controller\HomeController;

$router->get('/', [HomeController::class, 'index'])
	->name('home');

$router->get('/about', [HomeController::class, 'about']);

$router->get('/auth', [HomeController::class, 'auth'])
	->middleware(\App\Middleware\AuthMiddleware::class);

$router->get('/test', function () {
	return json(route('about'));
});