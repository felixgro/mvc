<?php

namespace Core\Console;

use Symfony\Component\Console\Application;

class Kernel extends Application
{
	/**
	 * Handle the console execution for the application.
	 */
	public function handleExecution()
	{
		return $this->run();
	}
}

