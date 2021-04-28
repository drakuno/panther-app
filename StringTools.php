<?php

namespace PantherApp;

use PantherApp\Exception\AppException;

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

	static public function templateRender(string $template_filename,array $vars=array())
	{
		if (!is_file($template_filename))
			throw new AppException("template-not-found");

		extract($vars);
		ob_start();
		require $template_filename;
		return ob_get_clean();
	}

	static public function titleCase(string $str){ return ucfirst($str); }
}
