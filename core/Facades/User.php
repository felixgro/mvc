<?php

namespace Core\Facades;

use App\Models\User as BaseUser;

/**
 * @method static array all ()
 * @method static BaseUser first ()
 * @method static BaseUser find(mixed $key)
 *
 * @see \App\Models\User;
 */
class User extends Facade
{
	protected static function getFacadeAbstract(): string
	{
		return \App\Models\User::class;
	}
}