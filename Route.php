<?php

namespace PantherApp;

use PantherApp\Request;
use PantherApp\StringTools;
use PantherApp\Exception\AppException;

abstract class Route
{
	static private function handlesForString(string $target)
	{
		return function(Request $request) use ($target)
		{
			return $request->target==$target;
		};
	}

	static private function handlesForStringArray(array $targets)
	{
		if (count(array_filter($targets,function($target){ return is_string($target); }))>0)
			throw new AppException("wrong-type","All array elements must be of type <string>.");

		return function(Request $request) use ($targets)
		{
			return in_array($request->target,$targets);
		};
	}

	static private function handlesFrom($mixed)
	{
		if (is_callable($mixed))
			return $mixed;
		else if (is_string($mixed))
			return self::handlesForString($mixed);
		else if (is_array($mixed))
			return self::handlesForStringArray($mixed);
		else
			throw new AppException("invalid-route-handles","Could not normalize.");
	}

	static final public function normalization($value)
	{
		if (is_array($value))
			return new GenericRoute(self::handlesFrom($value['handles']),$value['handler']);
		else if (is_string($value))
			return new $value;
		else if (is_subclass_of($value,"Route"))
			return $value;
	}

	static final public function listNormalization(array $values)
	{
		return array_map(["Route","normalization"],$values);
	}

	public abstract function handle(Request $request); // Do request handling.
	public abstract function handles(Request $request); // Test if can handle request.
}
