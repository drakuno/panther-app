<?php

namespace PantherApp;

use PantherApp\Exception\AppException;

class Request
{
	private $query; //o3o
	private	$targetIndex	= 0;
	private	$vars;

	public	$state	= array();

	public function __construct($query=array(),array $vars=array())
	{
		$this->query	= self::queryNormalization($query);
		$this->vars		= $vars;
	}

	public static function queryArrayValidation(array $query=array())
	{
		return !in_array("/",$query);
	}

	public static function queryNormalization($query)
	{
		if (is_string($query))
			return self::queryFromString($query);
		else if (is_array($query)) {
			if (self::queryArrayValidation($query))
				return $query;
			else
				throw new AppException("invalid-value","Array supplied as query is invalid.");
		} else {
			$query_type	= gettype($query);
			throw new AppException("wrong-type","Unknown query format [${query_type}].",$query);
		}
	}

	public static function queryFromString(string $query="")
	{
		if (!is_string($query))
			throw new AppException("wrong-type");
		return explode("/",$query);
	}

	public function advance(){ $this->targetIndex++; }

	public function rewind(){ $this->targetIndex=0; }

	public function target()
	{
		if ($this->targetIndex<count($this->query))
			return $this->query[$this->targetIndex];
		else
			return "";
	}

	public function query(){ return $this->query; }

	public function remainder():string
	{
		return implode("/",$this->slice());
	}

	public function slice():array
	{
		return array_slice($this->query,$this->targetIndex);
	}

	public function vars(){ return $this->vars; }
}
