<?php 

namespace app\api\exception;

use think\Exception\Handle;
use Exception;
use think\Log;

class ExceptionHandler extends Handle 
{
	private $statusCode;
	private $msg;
	private $errorCode;

	public function render(Exception $e)
	{
		if ( $e instanceof BaseException ){
			$this->statusCode = $e->statusCode;
			$this->msg = $e->msg;
			$this->errorCode = $e->errorCode;
		}else{
			if ( config('app_debug') ){
				return parent::render($e);
			}
			// internal server error 
			$this->statusCode = 500;
			$this->msg = 'we encounter an error -_- => '.$e->getMessage();
			$this->errorCode = 999;

			$this->recordErrorLog($e);
		}

		$result = [
			'code'      =>	$this->errorCode,
			'msg'		=>	$this->msg,
			'url'		=>	request()->url()
		];

		return json($result, $this->statusCode);
	}

	/*
     * 将异常写入日志
     */
    private function recordErrorLog(Exception $e)
    {
        Log::init([
            'type'  =>  'File',
            'path'  =>  LOG_PATH,
            'level' => ['error']
        ]);
       // Log::record($e->getTraceAsString());
        Log::record($e->getMessage(),'error');
    }
}