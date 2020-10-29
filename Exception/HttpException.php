<?php

namespace PantherApp\Exception;

use PantherApp\Exception\AppException;

class HttpException extends AppException
{
	public function __construct($http_response_code_or_slug,string $message="",$data=array())
	{
		if (is_int($http_response_code_or_slug)) {
			$http_response_code	= $http_response_code_or_slug;
			$slug				= self::responseCodeSlug($http_response_code);
		} else {
			$http_response_code	= 500;
			$slug				= $http_response_code_or_slug;
		}
		http_response_code($http_response_code);
		parent::__construct($slug,$message,$data);
	}

	static public function responseCodeSlug(int $http_response_code){ return "http-${http_response_code}"; }
}