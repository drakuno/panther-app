<?php

namespace PantherApp;

use \ArrayIterator;

class ArrayTools
{
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

	static public function filterAnd(callable ...$filters):callable
	{
		if (count($filters)==1)
			return $filters[0];
		else {
			$leftside	= $filters[0];
			if (count($filters)==2)
				$rightside	= $filters[1];
			else
				$rightside	= self::filterAnd(array_slice($filters,1));
			return function(...$args) use ($leftside,$rightside):bool
			{
				return call_user_func_array($leftside,$args)&&call_user_func_array($rightside,$args);
			};
		}
	}

	static public function filterNot(callable $filter):callable
	{
		return function(...$args) use ($filter)
		{
			return !call_user_func_array($filter,$args);
		};
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
}