<?php

require_once 'vendor/autoload.php';

$router = new \App\Lib\Http\Router('app/Http/routes.php');
$router->loadRoutes();

$request = \App\Lib\Http\Request::createFromGlobals();

$dispatcher = new \App\Lib\Core\EventDispatcher();

// Vite Dev Middleware
$dispatcher->addListener('kernel.request', function (\Symfony\Component\HttpKernel\Event\RequestEvent $event) {
    if (!viteServerRunning()) return;

    $request = $event->getRequest();
	$path = $request->getPathInfo();

    if (str_starts_with($path, '/assets')) {
		header("Location: http://localhost:5134" . $path);
		exit;
    }
});

$controllerResolver = new App\Lib\Http\ControllerResolver($router);

$kernel = new \App\Lib\Http\Kernel($dispatcher, $controllerResolver);

try {
	$response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);
} catch (Throwable $exception) {
	dump('exception in http kernel:');
	dd($exception);
}