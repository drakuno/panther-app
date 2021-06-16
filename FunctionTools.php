<?php

namespace PantherApp;

class FunctionTools
{
	static public function asResultMap($argsprovider=null):callable
	{
		if (!empty($argsprovider)&&!is_array($argsprovider)&&!is_callable($argsprovider))
			throw new TypeError("expected array or callable");

		return function(callable $callable) use ($argsprovider)
		{
			if (is_array($argsprovider))
				$args = $argsprovider;
			else if (is_callable($argsprovider))
				$args = $argsprovider($callable);
			else
				$args = array();

			return call_user_func_array($callable,$args);
		};
	}

	static public function paramBindDecoration(
		callable $call,
		array $bindings
	)
	{
		return function() use ($call,$bindings)
		{
			$mixed_arguments	= array();
			for ($i=0,$j=0;$i<count($bindings)+func_num_args();$i++)
				$mixed_arguments[] = array_key_exists($i,$bindings)
										?$bindings[$i]
										:func_get_arg($j++);
			return call_user_func_array($call,$mixed_arguments);
		};
	}

	static public function pipe(callable ...$funcs)
	{
		return function($arg) use ($funcs)
		{
			foreach ($funcs as $func)
				$arg = call_user_func($func,$arg);
			return $arg;
		};
	}
}