<?php

namespace app\api\controller\v1;

use app\api\controller\Base;
use think\Request;
use app\api\model\Blog as BlogModel;

class Blog extends Base
{
		public function detail(Request $request)
		{
			(new IDMustBePositiveInt())->goCheck();
			$blog = BlogModel::get($request->param('id'));

			if ( !$blog ){
				_error('blog item not exists');
			}

			return _success($blog);
		}

		public function read(Request $request)
		{
			 $page = $request->param('page', 1);
			 $count = $request->param('count', 10);
			 $result = BlogModel::getList($page, $count);

			 return _success($result);
		}
    public function add(Request $request)
    {
        [
            'content' => '',
            'images'  => '',
        ];
        $content = $request->post('content');
        $images = $request->post('images/a');
        $data = [
        	'content'	=>	$content,
        	'user_id'	=>	$this->userId,
        	'images'	=>	$images,
        ];     
        $result = BlogModel::create($data);

        return _success($result);
    }

    public function upload(Request $request)
    {
        $files    = $request->file('file');
        $savePath = ROOT_PATH . 'public' . DS . 'uploads';
        $fileUrl = [];
        $flag = true;
        // var_dump(is_array($files));die;
        foreach( $files as $file ){
	        $info     = $file
	            ->validate(['ext' => 'png,jpg,jpeg,gif', 'size' => 1024 * 1024 * 5])
	            ->move($savePath);
	        if ($info) {
	            array_push($fileUrl, '/uploads' . DS . $info->getSaveName());
	        } else {
	            $flag = false;
	            return;
	        }
	      }
	      if ( $flag ){
	      	return _success($fileUrl);
	      }
	      return _error('upload failure'); 
    }
}
