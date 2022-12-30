<?php

namespace App\Core\Contracts;

use App\Core\Http\Request;

interface Middleware
{
	public static function handle(Request $request): void;
}