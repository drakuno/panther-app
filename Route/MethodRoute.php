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
		$target	= $request->target();
		$suffix	= empty($target)?"Empty":StringTools::slugToCamelCase($request->target(),true);
		$method	= "handle${suffix}";

		if (method_exists(get_called_class(),$method))
			return call_user_func(array($this,$method),$request);
		else
			throw new AppException("no-matching-route","No registered route handles such request.");
	}
}