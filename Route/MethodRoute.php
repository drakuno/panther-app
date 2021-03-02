<?php

namespace PantherApp\Route;

use PantherApp\Request;
use PantherApp\Route;
use PantherApp\StringTools;
use PantherApp\Exception\AppException;

abstract class MethodRoute extends Route
{
	public function handle(Request $request)
	{
		$method_name = static::targetAsMethodNameMap($request->target());

		if (method_exists(get_called_class(),$method_name))
			return call_user_func(array($this,$method_name),$request);
		else
			throw new AppException("no-matching-route","No registered route handles such request.");
	}

	static public function targetAsMethodNameMap(string $target)
	{
		$suffix	= empty($target)
					?"Empty"
					:StringTools::slugToCamelCase($target,true);
		return "handle${suffix}";
	}
}