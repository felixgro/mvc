<?php

namespace Core\Auth\Contracts;

interface UserFactoryInterface
{
	public function storeUser(array $data): bool;

	public function getUserBy(string $key, mixed $value): mixed;

	public function deleteUser(string $id): mixed;

	public function verifyPassword(mixed $user, string $password): bool;
}