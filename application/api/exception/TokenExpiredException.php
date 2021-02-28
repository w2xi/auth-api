<?php 

namespace app\api\exception;

class TokenExpiredException extends BaseException
{
	public $statusCode = 401;
	public $msg = 'token is expired';
	public $errorCode = 1000;
}