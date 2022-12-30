<?php

/** @var App\Core\Http\Router $router */

use App\Controller\HomeController;
use App\Core\Http\Middleware\{
	ViteMiddleware,
	AuthMiddleware
};

$router->get('/', [HomeController::class, 'index']);

$router->get('/about', [HomeController::class, 'about']);

$router->get('/auth', [HomeController::class, 'auth']);
// ->middleware('auth')
// ->name('auth');