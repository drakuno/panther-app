<?php

namespace PantherApp\Exception;

use PantherApp\Exception\AppException;

class HttpException extends AppException
{
	protected	$http_status_code;

	public function __construct(
		$http_status_code_or_slug,
		string $message="",
		$data=null
	)
	{
		if (is_int($http_status_code_or_slug)) {
			$this->http_status_code	= $http_status_code_or_slug;
			$slug					= self::responseCodeSlug($this->http_status_code);
		} else {
			$this->http_status_code	= 500;
			$slug					= $http_status_code_or_slug;
		}
		parent::__construct($slug,$message,$data);
	}

	static public function responseCodeSlug(int $http_status_code){ return "http-${http_status_code}"; }

	public function getHttpStatusCode(){ return $this->http_status_code; }
}