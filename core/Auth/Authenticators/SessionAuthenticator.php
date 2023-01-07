<?php

namespace Core\Auth\Authenticators;

use Core\Auth\Contracts\AuthenticatorInterface;
use Core\Auth\Contracts\UserFactoryInterface;

class SessionAuthenticator implements AuthenticatorInterface
{
	private UserFactoryInterface $userFactory;

	private string $hash = PASSWORD_DEFAULT;

	private array $currentUser;

	public function __construct(UserFactoryInterface $userFactory)
	{
		$this->userFactory = $userFactory;

		if (!session_id()) {
			session_start();
		}
	}

	public function login(string $id, string $password, bool $remember = false): bool
	{
		$user = $this->userFactory->getUserBy('email', $id);

		dd($remember);

		if ($user && $this->userFactory->verifyPassword($user, $password)) {
			$_SESSION['user_id'] = $user->id;
			return true;
		}

		return false;
	}

	public function logout(): bool
	{
		return session_destroy();
	}

	public function isAuthenticated(): bool
	{
		return array_key_exists('user_id', $_SESSION);
	}

	public function getCurrentUser(): mixed
	{
		if (isset($this->currentUser)) {
			return $this->currentUser;
		}

		$this->currentUser = $this->userFactory->getUserBy('id', $_SESSION['user_id']);
		unset($this->currentUser->password);
		return $this->currentUser;
	}

	private function hashPassword(string $password): string
	{
		return password_hash($password, $this->hash);
	}
}