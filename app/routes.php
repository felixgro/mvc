<?php

/** @var Core\Http\Router $router */

use App\Controller\{HomeController, AuthController};

$router->get('/', [HomeController::class, 'index'])
	->name('home');

$router->get('/logout', [HomeController::class, 'logout']);

$router->get('/about', [HomeController::class, 'about']);

$router->get('/auth', [HomeController::class, 'auth'])
	->middleware(\App\Middleware\AuthMiddleware::class);

$router->get('/test', [HomeController::class, 'test']);

$router->post('/auth/login', [AuthController::class, 'login']);