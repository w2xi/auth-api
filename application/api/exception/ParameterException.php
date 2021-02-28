<?php 

namespace app\api\exception;

class ParameterException extends BaseException
{
	public $statusCode = 400;
	public $msg = 'invalid parameter';
	public $errorCode = 999;
}
