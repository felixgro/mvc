<?php

/** @var App\Lib\Http\Router $router */

use App\Controller\HomeController;
use App\Lib\Http\Middleware\{
	ViteMiddleware,
	AuthMiddleware
};

// TODO: Move in service provider
$router->setGlobalMiddleware([
	AuthMiddleware::class,
	ViteMiddleware::class
]);

/*
$router->setDevMiddleware([
	ViteMiddleware::class
]);
*/

$router->get('/', [HomeController::class, 'index']);

$router->get('/about', [HomeController::class, 'about']);

$router->get('/auth', [HomeController::class, 'auth']);
// ->middleware('auth')
// ->name('auth');