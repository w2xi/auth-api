<?php 

namespace app\api\exception;

class JwtTokenExpiredException extends BaseException
{
	public $statusCode = 401;
	public $msg = 'token is expired';
	public $errorCode = 1000;
}