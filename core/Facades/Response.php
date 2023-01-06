<?php

namespace Core\Facades;

use Core\Http\Response as BaseResponse;

/**
 * @method static BaseResponse redirect(string $to)
 * @method static BaseResponse setContent(string $content)
 * @method static BaseResponse setStatusCode(int $status)
 *
 * @see \Core\Http\Response
 */
class Response extends Facade
{
	protected static function getFacadeAbstract(): string
	{
		return BaseResponse::class;
	}
}