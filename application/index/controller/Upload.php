<?php 

namespace app\index\controller;

class Upload 
{
	public function index()
	{
		return '<!-- The data encoding type, enctype, MUST be specified as below -->
<form enctype="multipart/form-data" action="upload" method="POST">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
    <!-- Name of input element determines name in $_FILES array -->
    Send this file: <input name="file" type="file" />
    <input type="submit" value="Send File" />
</form>';
	}

	public function upload(\think\Request $request)
	{
		$file = $request->file('file');

		echo '<pre>';
		var_dump($file);
	}
}