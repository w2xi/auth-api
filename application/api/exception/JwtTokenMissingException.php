<?php 

namespace app\api\exception;

class JwtTokenMissingException extends BaseException
{
	public $statusCode = 403;
	public $msg = 'No JWT token found in request headers';
	public $code = 1001;
}