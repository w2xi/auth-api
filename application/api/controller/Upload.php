<?php 

namespace app\api\controller;

class Upload
{
	protected static $instance;

	protected $config = [
		'ext'			=>	['png', 'jpg', 'jpeg', 'gif'],
		'max_size'=>	1024*1024*5,
	];

	private function __construct($config = [])
	{
		$this->config = array_merge($this->config, $config);
	}

	private function __clone() {}

	public static function instance($config = [])
	{
		if ( is_null(self::$instance) ){
			self::$instance = new static($config);
		} 

		return self::$instance; 
	}

	public function validate($options = [])
	{
		$ext = [];
		if ( isset($options['ext']) ){
			$ext = $options['ext'];
			$ext = is_array($ext) ?: explode(',', $ext);
		}
		$ext = $ext ?: $this->config['ext'];
		if ( !in_array($this->getUploadFileExt, $ext) ){
			$this->error = 'invalid extension';
			return $this;
		}
		$size = $options['size'] ?? $this->config['max_size'];
		if ( $this->getUploadFileSize > $size ){
			$this->error = 'exceed max upload size';
			return $this;
		}

		return $this;
	}

	public function move()
	{

	}

	public function getExtension()
	{
		return pathinfo($this->file['name'], PATHINFO_EXTENSION);
	}

	public function getFileName()
	{

	}

	public function getSaveName()
	{

	}

	public function setUploadFile($file)
	{
		$this->file = $file;
		return $this;
	}

	public function getUploadFile()
	{
		return $this->file;
	}

	private function getUploadFileExt()
	{
		return pathinfo($this->file['name'], PATHINFO_EXTENSION);
	}

	private function getUploadFileSize()
	{
		return $this->file['size'];
	}
}