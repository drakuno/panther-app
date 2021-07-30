<?php

namespace PantherApp;

use PantherApp\Exception\AppException;

class StringTools
{
	static public function camelAsDelimitedCaseMap(string $str,string $delimiter):string
	{
		return strtolower(implode($delimiter,static::caseExplode($str)));
	}

	static public function camelAsKebabCaseMap(string $str):string
	{
		return static::camelAsDelimitedCaseMap($str,"-");
	}

	static public function camelAsSnakeCaseMap(string $str):string
	{
		return static::camelAsDelimitedCaseMap($str,"-");
	}

	static public function caseExplode(string $str):array
	{
		return preg_split("/(?=[A-Z])/",$str);
	}

	static public function concat(string ...$strings)
	{
		return implode("",$strings);
	}

	static public function delimitedAsCamelCaseMap(string $str,string $delimiter):string
	{
		return lcfirst(static::delimitedAsPascalCaseMap($str,$delimiter));
	}

	static public function delimitedAsPascalCaseMap(string $str,string $delimiter):string
	{
		return implode("",array_map("ucfirst",explode($delimiter,$str)));
	}

	static public function endsWith(string $str,string $suffix)
	{
		return substr($str,-strlen($suffix))==$suffix;
	}

	static public function kebabAsCamelCaseMap(string $str):string
	{
		return static::delimitedAsCamelCaseMap($str,"-");
	}

	static public function kebabAsPascalCaseMap(string $str):string
	{
		return static::delimitedAsPascalCaseMap($str,"-");
	}

	static public function longerThanFilterMake($target):callable
	{
		$length = is_string($target)?strlen($target):intval($target);
		return function(string $str) use ($length)
		{
			return strlen($str)>$length;
		};
	}

	static public function pascalAsDelimitedCaseMap(string $str,string $delimiter):string
	{
		return strtolower(implode($delimiter,array_filter(static::caseExplode($str))));
	}

	static public function pascalAsKebabCaseMap(string $str):string
	{
		return static::pascalAsDelimitedCaseMap($str,"-");
	}

	static public function pascalAsSnakeCaseMap(string $str):string
	{
		return static::pascalAsDelimitedCaseMap($str,"_");
	}

	static public function prefix(string $str,string $prefix)
	{
		return static::concat($prefix,$str);
	}

	static public function shorterThanFilterMake($target):callable
	{
		$length = is_string($target)?strlen($target):intval($target);
		return function(string $str) use ($length)
		{
			return strlen($str)<$length;
		};
	}

	/**
	 * @deprecated - use kebabAsCamelCaseMap or kebabAsPascalCaseMap
	 *		instead!
	 */
	static public function slugToCamelCase(string $str,bool $includingFirstWord=false)
	{
		if ($includingFirstWord)
			return static::kebabAsPascalCaseMap($str);
		else
			return static::kebabAsCamelCaseMap($str);
	}

	/**
	 * @deprecated - use kebabAsPascalCaseMap instead!
	 */
	static public function slugToPascalCase(string $str):string
	{
		return static::kebabAsPascalCaseMap($str);
	}

	static public function snakeAsCamelCaseMap(string $str):string
	{
		return static::delimitedAsCamelCaseMap($str,"_");
	}

	static public function snakeAsPascalCaseMap(string $str):string
	{
		return static::delimitedAsPascalCaseMap($str,"_");
	}

	/**
	 * @deprecated - use snakeAsCamelCaseMap or snakeAsPascalCaseMap
	 *		instead!
	 */
	static public function snakeToCamelCase(string $str,bool $includingFirstWord=false)
	{
		if ($includingFirstWord)
			return static::snakeAsPascalCaseMap($str);
		else
			return static::snakeAsCamelCaseMap($str);
	}

	/**
	 * @deprecated - use snakeAsPascalCaseMap instead!
	 */
	static public function snakeToPascalCase(string $str):string
	{
		return static::snakeAsPascalCaseMap($str);
	}

	static public function spacedAsCamelCaseMap(string $str):string
	{
		return static::delimitedAsCamelCaseMap($str," ");
	}

	static public function spacedAsPascalCaseMap(string $str):string
	{
		return static::delimitedAsPascalCaseMap($str," ");
	}

	/**
	 * @deprecated - use spacedAsCamelCaseMap or spacedAsPascalCaseMap
	 *		instead!
	 */
	static public function spacedToCamelCase(string $str,bool $includingFirstWord=false):string
	{
		if ($includingFirstWord)
			return static::spacedAsPascalCaseMap($str);
		else
			return static::spacedAsCamelCaseMap($str);
	}

	/**
	 * @deprecated - use spacedAsPascalCaseMap instead!
	 */
	static public function spacedToPascalCase(string $str):string
	{
		return static::spacedAsPascalCaseMap($str);
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
		$context=null,
		&$template_return=null
	)
	{
		if (!is_file($template_filename))
			throw new AppException("template-not-found");

		$contextualRender = function() use (
			$template_filename,
			$vars,
			&$template_return
		)
		{
			extract($vars,EXTR_SKIP);
			ob_start();
			$template_return = require $template_filename;
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
