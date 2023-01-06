<?php

namespace Core\Auth\UserFactories;

use Core\Database\Database;

class MysqlUserFactory implements UserFactoryInterface
{
	private Database $db;

	private string $hash = PASSWORD_DEFAULT;

	public function __construct(Database $db)
	{
		$this->db = $db;
	}

	public function storeUser(array $data): bool
	{
		$queryRes = $this->db->query("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)", [
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => $this->hashPassword($data['password'])
		]);

		return is_array($queryRes);
	}

	public function getUserBy(string $key, mixed $value): mixed
	{
		if ($key !== 'email' && $key !== 'id') return false;

		if ($key === 'email') {
			$userQuery = $this->db->query("SELECT * FROM users WHERE email = :email", [
				'email' => $value
			]);
		} else {
			$userQuery = $this->db->query("SELECT * FROM users WHERE id = :id", [
				'id' => $value
			]);
		}
		
		if (!empty($userQuery) && $user = $userQuery[0]) {
			return $user;
		}

		return false;
	}

	public function deleteUser(mixed $user): mixed
	{
		// TODO: Implement deleteUser() method.
	}

	public function verifyPassword(mixed $user, string $password): bool
	{
		return password_verify($password, $user->password);
	}

	private function hashPassword(string $password): string
	{
		return password_hash($password, $this->hash);
	}
}