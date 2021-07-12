<?php

namespace PantherApp;

use ArrayIterator;
use TypeError;

class ArrayTools
{
	static public function asMap(array $arr,$default=null):callable
	{
		return function($key) use ($arr,$default)
		{
			return array_key_exists($key,$arr)
					 ?$arr[$key]
					 :(is_callable($default)
					 	?call_user_func($default,$key)
					 	:$default);
		};
	}

	static public function every(array $arr,callable $filter):bool
	{
		$iterator	= new ArrayIterator($arr);

		while ($iterator->valid())
			if ($filter($iterator->current()))
				$iterator->next();
			else
				return false;
		return true;
	}

	/**
	 * Deprecated: moved to FunctionTools::and()
	 */
	static public function filterAnd(callable ...$filters):callable
	{
		return FunctionTools::and(...$filters);
	}

	/**
	 * Deprecated: moved to FunctionTools::not()
	 */
	static public function filterNot(callable $filter):callable
	{
		return FunctionTools::not($filter);
	}

	static public function find(array $arr,callable $filter,$default=null)
	{
		$key	= self::search($arr,$filter);
		return $key!==false?$arr[$key]:$default;
	}

	static public function fromArrayOfTuples(array $key_value_tuples):array
	{
		return array_combine(
			array_column($key_value_tuples,0),
			array_column($key_value_tuples,1)
		);
	}

	static public function get(array $arr,$key,$default=null)
	{
		return array_key_exists($key,$arr)?$arr[$key]:$default;
	}

	static public function keyAccessCallable($key):callable
	{
		return function(array $arr) use ($key){ return $arr[$key]; };
	}

	static public function keyAndValueMap(array $arr,callable $map):array
	{
		$keys				= array_keys($arr);
		$key_value_tuples	= array_map($map,$keys,$arr);
		return self::fromArrayOfTuples($key_value_tuples);
	}

	static public function keyValueFilter($key,$value)
	{
		return function($arr) use ($key,$value)
		{
			return $arr[$key]==$value;
		};
	}

	static public function keysExclude(array $arr,array $keys):array
	{
		return array_diff_key($arr,array_flip($keys));
	}

	static public function keysExist(array $arr,array $keys):bool
	{
		return static::every(
			$keys,
			FunctionTools::paramBindDecoration(
				"array_key_exists",
				[1=>$arr]
			)
		);
	}

	static public function keysMap(array $arr,callable $map):array
	{
		$keys	= array_keys($arr);
		return array_combine(
			array_map($map,$keys),
			$arr
		);
	}

	static public function keysPrefix(array $arr,string $prefix):array
	{
		return self::keysMap(
			$arr,
			function($key) use ($prefix) { return $prefix.$key; }
		);
	}

	static public function keysSlice(array $arr,array $keys)
	{
		return array_intersect_key(
			$arr,
			array_flip($keys)
		);
	}

	static public function pick(array $arr,array $keys)
	{
		return self::keysSlice($arr,$keys);
	}

	static public function search(array $arr,callable $filter)
	{
		$iterator	= new ArrayIterator($arr);

		while ($iterator->valid())
			if ($filter($iterator->current()))
				return $iterator->key();
			else
				$iterator->next();
		return false;
	}

	static public function some(array $arr,callable $filter):bool
	{
		return self::search($arr,$filter)!==false;
	}

	static public function valueAsAssocMap(array $mappings):callable
	{
		if (!static::every($mappings,"is_callable"))
			throw new TypeError("expected array of callables");

		return function($value) use ($mappings):array
		{
			return array_map(
				FunctionTools::asResultMap([$value]),
				$mappings
			);
		};
	}
}