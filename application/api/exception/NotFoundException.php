<?php 

namespace app\api\exception;

class NotFoundException extends BaseException
{
	public $statusCode = 404;
	public $msg = 'url not found';
	public $errorCode = 1002;
}