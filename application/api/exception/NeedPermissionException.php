<?php 

namespace app\api\exception;

class NeedPermissionException extends BaseException
{
	public $statusCode = 403;
	public $msg = 'need permission';
	public $errorCode = 1001;
}