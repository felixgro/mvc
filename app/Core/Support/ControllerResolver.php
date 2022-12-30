<?php

namespace App\Core\Support;

use App\Core\Http\Request;
use App\Core\Http\Router;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

class ControllerResolver implements ControllerResolverInterface
{
	private Router $router;

	public function __construct(Router $router)
	{
		$this->router = $router;
	}

	public function getController(Request|\Symfony\Component\HttpFoundation\Request $request): callable|false
	{
		return $this->router->getAction($request);
	}
}