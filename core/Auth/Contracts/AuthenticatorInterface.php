<?php

namespace Core\Auth\Contracts;

interface AuthenticatorInterface
{
	public function login(string $id, string $password): bool;

	public function logout(): bool;

	public function isAuthenticated(): bool;

	public function getCurrentUser(): mixed;
}