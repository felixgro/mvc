<?php

namespace Core\Facades;

use Core\View\Template\Template;

/**
 * @method static Template make(string $name)
 *
 * @see \Core\Support\View
 */
class View extends Facade
{
	protected static function getFacadeAbstract(): string
	{
		return \Core\View\View::class;
	}
}