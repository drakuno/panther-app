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

	static public function prefix(string $str,string $prefix)
	{
		return static::concat($prefix,$str);
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

	static public function slugToPascalCase(string $str):string
	{
		return static::slugToCamelCase($str,true);
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

	static public function snakeToPascalCase(string $str):string
	{
		return static::snakeToCamelCase($str,true);
	}

	static public function startsWith(string $str,string $prefix)
	{
		return strpos($str,$prefix)===0;
	}

	static public function suffix(string $str,string $suffix)
	{
		return static::concat($str,$suffix);
	}

	static public function templateRender(
		string $template_filename,
		array $vars=array(),
		$context=null
	)
	{
		if (!is_file($template_filename))
			throw new AppException("template-not-found");

		$contextualRender = function() use ($template_filename,$vars)
		{
			extract($vars,EXTR_SKIP);
			ob_start();
			require $template_filename;
			return ob_get_clean();
		};

		return $contextualRender->bindTo(
			is_object($context)?$context:null,
			$context
		)();
	}

	static public function titleCase(string $str)
	{
		return ucfirst(strtolower($str));
	}

	static public function wrap(string $str,string $wrap)
	{
		return static::concat($wrap,$str,$wrap);
	}

	static public function quote(string $str,string $quote='"')
	{
		return static::wrap($str,$quote);
	}
}
