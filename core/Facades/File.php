<?php

namespace Core\Facades;

/**
 * @method static mixed require (string $path)
 *
 * @see \Core\Support\File
 */
class File extends Facade
{
	protected static function getFacadeAbstract(): string
	{
		return \Core\Support\File::class;
	}
}