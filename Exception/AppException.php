<?php

namespace PantherApp\Exception;

use \Exception;

class AppException extends Exception
{
	protected	$data;
	protected	$slug;
	public function __construct($slug,$message="",$data=array())
	{
		$this->data	= $data;
		$this->slug	= $slug;
		parent::__construct($message);
	}

	public function __toString()
	{
		$message	= $this->getMessage();
		$slug		= $this->slug;
		if (!empty($message))
			return "(${slug}) ${message}";
		else
			return $slug;
	}

	public function getData(){ return $this->data; }

	public function getSlug(){ return $this->slug; }
}
