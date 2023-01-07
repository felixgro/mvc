<?php

namespace App\Models;

use Core\Database\Model;

class User extends Model
{
	protected string $tableName = 'users';

	protected array $attributes = [
		'id', 'name', 'email', 'password', 'remember_token', 'created_at', 'updated_at'
	];
}