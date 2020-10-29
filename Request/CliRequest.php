<?php

namespace PantherApp\Request;

use PantherApp\Request;
use PantherApp\Exception\AppException;

class CliRequest extends Request
{
	// Verifies that PHP is currently serving a command line request
	static public function environmentVerification()
	{
		return !empty($_SERVER)&&!empty($_SERVER['argv']);
	}

	public function __construct(array $vars=array())
	{
		if (!self::environmentVerification())
			throw new AppException("wrong-environment","PHP is not currently serving a command line request.");
		parent::__construct(array_slice($_SERVER['argv'],1),$vars);
	}

}