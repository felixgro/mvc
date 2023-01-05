<?php

namespace Core\View\Extension;

use Core\View\View;

/**
 * A common interface for extensions.
 */
interface ExtensionInterface
{
	public function register(View $engine);
}
