<?php 

namespace app\api\controller\v1;

use think\Request;
use app\api\controller\Base;

class Blog extends Base 
{
	public function add(Request $request)
	{
	    [
	        'content'   =>  '',
            'images'    =>  ''
        ];
		$data = $request->post();
		var_dump($data);
	}

	public function upload(Request $request)
	{
		$file = $request->file('file');
		$savePath = ROOT_PATH.'public'.DS.'uploads';
		$info = $file
                ->validate(['ext'=>'png,jpg,jpeg,gif', 'size'=>1024*1024*5])
                ->move($savePath);
		if ( $info ){
			return _success(['url'=>'/uploads'.DS.$info->getSaveName()]);
		}else{
			return _error('upload failure');
		}
	}
}