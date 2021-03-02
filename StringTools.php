<?php

namespace PantherApp;

class StringTools
{
	static public function concat(string ...$strings)
	{
		return implode("",$strings);
	}

	static public function endsWith(string $str,string $suffix)
	{
		return substr($str,-strlen($suffix))==$suffix;
	}

	static public function slugToCamelCase(string $str,bool $includingFirstWord=false)
	{
		$words			= explode("-",$str);
		$wordsToCamel	= $includingFirstWord?$words:array_slice($words,1);
		$camelWords		= array_map("ucfirst",$wordsToCamel);
		$camel			= implode("",$camelWords);
		if ($includingFirstWord)
			return $camel;
		else
			return $words[0].$camel;
	}

	static public function snakeToCamelCase(string $str,bool $includingFirstWord=false)
	{
		$words			= explode("_",$str);
		$wordsToCamel	= $includingFirstWord?$words:array_slice($words,1);
		$camelWords		= array_map("ucfirst",$wordsToCamel);
		$camel			= implode("",$camelWords);
		if ($includingFirstWord)
			return $camel;
		else
			return $words[0].$camel;
	}

	static public function startsWith(string $str,string $prefix)
	{
		return substr($str,0,strlen($prefix))==$prefix;
	}

	static public function titleCase(string $str){ return ucfirst($str); }
}
