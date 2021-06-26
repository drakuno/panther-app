<?php

namespace PantherApp;

use ReflectionFunction;
use ReflectionMethod;
use ReflectionParameter;

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

	static public function and(callable ...$filters):callable
	{
		if (count($filters)==1)
			return $filters[0];
		else {
			$leftside	= $filters[0];
			if (count($filters)==2)
				$rightside	= $filters[1];
			else
				$rightside	= self::and(array_slice($filters,1));
			return function(...$args) use ($leftside,$rightside):bool
			{
				return call_user_func_array($leftside,$args)&&call_user_func_array($rightside,$args);
			};
		}
	}

	static public function identity($value):callable
	{
		return function() use ($value){ return $value; };
	}

	static public function not(callable $filter):callable
	{
		return function(...$args) use ($filter)
		{
			return !call_user_func_array($filter,$args);
		};
	}

	static public function or(callable ...$funcs):callable
	{
		return function() use ($funcs)
		{
			return ArrayTools::some(
				$funcs,
				self::asResultMap(func_get_args())
			);
		};
	}

	static public function paramAsPhpMap(
		ReflectionParameter $param,
		int $index=null
	):string
	{
		return sprintf('%s$arg%s',
			$param->isPassedByReference()?"&":"",
			is_numeric($index)?$index:""
		);
	}

	static public function paramBindDecoration(
		callable $call,
		array $bindings,
		bool $forward_references=false
	)
	{
		if (!$forward_references)
			return function() use ($call,$bindings)
			{
				$mixed_arguments = array();
				for ($i=0,$j=0;$i<count($bindings)+func_num_args();$i++)
					$mixed_arguments[] = array_key_exists($i,$bindings)
											?$bindings[$i]
											:func_get_arg($j++);
				return call_user_func_array($call,$mixed_arguments);
			};
		else {
			$reflection			= is_array($call)
									?new ReflectionMethod($call[0],$call[1])
									:new ReflectionFunction($call);
			$unbound_parameters = array_diff_key(
				$reflection->getParameters(),
				$bindings
			);
			$unbound_args_list = implode(",",array_map(
				[self::class,"paramAsPhpMap"],
				$unbound_parameters,
				range(0,count($unbound_parameters)-1)
			));
			return eval(sprintf(
				'return function(%1s) use ($call,&$bindings)
				{
					$mixed_arguments = array();
					for ($i=0,$j=0;$i<count($bindings)+func_num_args();$i++)
						if (array_key_exists($i,$bindings))
							$mixed_arguments[] = &$bindings[$i];
						else
							$mixed_arguments[] = &${"arg".$j++};
					return call_user_func_array($call,$mixed_arguments);
				};',
				$unbound_args_list
			));
		}
	}

	static public function pipe()
	{
		$funcs = func_get_args();
		if (is_array($funcs[0])) {
			$return_encapsulation_indices = $funcs[0];
			$funcs						  = array_slice($funcs,1);
		} else
			$return_encapsulation_indices = array();

		return function() use ($funcs,$return_encapsulation_indices)
		{
			$args = func_get_args();
			foreach ($funcs as $index=>$func) {
				$args = call_user_func_array(
					$func,
					!is_array($args)?[$args]:$args
				);
				if (in_array($index,$return_encapsulation_indices))
					$args = [$args];
			}
			return $args;
		};
	}
}