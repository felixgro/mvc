<?php

namespace Core\Auth;

use Core\Auth\Authenticators\SessionAuthenticator;
use Core\Auth\Contracts\AuthenticatorInterface;
use Core\Auth\Contracts\UserFactoryInterface;
use Core\Auth\UserFactories\MysqlUserFactory;
use Core\Providers\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->app->singleton(Auth::class);

		$this->app->singleton(
			UserFactoryInterface::class,
			MysqlUserFactory::class
		);

		$this->app->singleton(
			AuthenticatorInterface::class,
			SessionAuthenticator::class
		);
	}

	public function boot()
	{
		//
	}
}