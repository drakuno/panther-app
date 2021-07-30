<?php

namespace PantherApp\Request;

use PantherApp\Config;
use PantherApp\Request;
use PantherApp\Exception\AppException;

class HttpRequest extends Request
{
	// Verifies that PHP is currently serving an HTTP request
	static public function environmentVerification()
	{
		return !empty($_SERVER)&&array_key_exists("REQUEST_METHOD",$_SERVER);
	}

	static public function URIAsAppQueryMap(string $uri,string $base_path="")
	{
		$query	= self::URIPathBaseTrim($uri,$base_path);

		if (($uriquery_pos=strpos($query,"?"))!==false)
			$query	= substr($query,0,$uriquery_pos);

		return $query;
	}

	static public function URIPathBaseTrim(string $uri,string $base_path="")
	{
		if (!self::URIPathBaseValidation($uri,$base_path))
			throw new AppException("no-match","Cannot match URI [${uri}] to specified base-path [${base_path}].");

		return substr($uri,strlen($base_path));
	}

	// Verifies that the provided URI matches the configured base-path
	static public function URIPathBaseValidation(string $uri,string $base_path="")
	{
		return strpos($uri,$base_path)===0;
	}

	public function __construct(array $vars=array(),string $base_path="")
	{
		if (!self::environmentVerification())
			throw new AppException("wrong-environment","PHP is not currently serving an HTTP request.");

		$httpRequestPath	= self::URIAsAppQueryMap($_SERVER['REQUEST_URI'],$base_path);
		$vars				= array_merge(['http'=>['GET'=>$_GET,'POST'=>$_POST]],$vars);
		parent::__construct($httpRequestPath,$vars);
	}

}