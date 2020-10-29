<?php

namespace PantherApp;

use \ArrayAccess;
use PantherApp\Exception\AppException;

class Config implements ArrayAccess
{
	static protected $latest;
	protected $entries;

	public function __construct(array $entries=array())
	{
		$this->entries	= $entries;
		self::$latest	= $this;
	}

	static public function latest():Config
	{
		if (self::$latest)
			return self::$latest;
		else
			throw new AppException("uninitialized","No instance yet.");
	}

	public function __get($entry_name){ return $this->entryRead($entry_name); }

	public function entryRead($entry_name)
	{
		if (array_key_exists($entry_name,$this->entries))
			return $this->entries[$entry_name];
		else
			return null;
	}

	public function entryRemoval($entry_name){ unset($this->entries[$entry_name]); }

	public function entryWrite($entry_name,$entry_value){ $this->entries[$entry_name]=$entry_value; }

	public function offsetExists($key){ return array_key_exists($key,$this->entries); }
	public function offsetGet($key){ return $this->entryRead($key); }
	public function offsetSet($key,$val){ $this->entryWrite($key,$val); }
	public function offsetUnset($key){ $this->entryRemoval($key); }
}