<?php

/** @var App\Lib\Http\Router $router */

use App\Http\Controller\HomeController;

$router->get('/', [HomeController::class, 'index']);

$router->get('/about', [HomeController::class, 'about']);
   // ->middleware('auth')
   // ->name('home');