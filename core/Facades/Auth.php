<?php

namespace Core\Facades;

/**
 * @method static bool check ()
 * @method static mixed user ()
 * @method static bool register(array $data)
 * @method static bool login (string $id, string $password, bool $remember)
 * @method static bool logout ()
 *
 * @see \Core\Auth\Auth
 */
class Auth extends Facade
{
	protected static function getFacadeAbstract(): string
	{
		return \Core\Auth\Auth::class;
	}
}