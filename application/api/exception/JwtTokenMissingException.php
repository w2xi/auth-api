<?php 

namespace app\api\exception;

class JwtTokenMissingException extends BaseException
{
	public $statusCode = 400;
	public $msg = 'No JWT token found in request headers';
	public $errorCode = 1001;
}