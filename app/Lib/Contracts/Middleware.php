<?php

namespace App\Lib\Contracts;

use App\Lib\Http\Request;

interface Middleware
{
	public static function handle(Request $request): void;
}