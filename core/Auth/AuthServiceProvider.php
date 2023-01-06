<?php

namespace Core\Auth;

use Core\Auth\Authenticators\AuthenticatorInterface;
use Core\Auth\Authenticators\SessionAuthenticator;
use Core\Auth\UserFactories\MysqlUserFactory;
use Core\Auth\UserFactories\UserFactoryInterface;
use Core\Providers\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->app->singleton(UserFactoryInterface::class, function ($c) {
			return $c->resolve(MysqlUserFactory::class);
		});

		$this->app->singleton(AuthenticatorInterface::class, function ($c) {
			return $c->resolve(SessionAuthenticator::class);
		});

		$this->app->singleton(Auth::class);
	}

	public function boot()
	{
		//
	}
}