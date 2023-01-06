<?php

namespace Core\Auth;

use Core\Auth\Authenticators\AuthenticatorInterface;
use Core\Auth\UserFactories\UserFactoryInterface;
use Somnambulist\Components\Validation\Factory;

class Auth
{
	private AuthenticatorInterface $authenticator;
	private UserFactoryInterface $userFactory;

	public function __construct(AuthenticatorInterface $authenticator, UserFactoryInterface $userFactory)
	{
		$this->authenticator = $authenticator;
		$this->userFactory = $userFactory;
	}

	public function check(): bool
	{
		return $this->authenticator->isAuthenticated();
	}

	public function user(): mixed
	{
		if (!$this->authenticator->isAuthenticated()) {
			return false;
		}

		return $this->authenticator->getCurrentUser();
	}

	public function register(array $data): bool
	{
		return $this->userFactory->storeUser($data);
	}

	public function login(string $id, string $password, bool $remember = false): bool
	{
		return $this->authenticator->login($id, $password, $remember);
	}

	public function logout(): bool
	{
		return $this->authenticator->logout();
	}
}