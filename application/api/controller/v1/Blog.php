<?php

namespace app\api\controller\v1;

use app\api\controller\Base;
use app\api\validate\IDMustBePositiveInt;
use think\Request;
use app\api\model\Blog as BlogModel;

class Blog extends Base
{
    public function detail(Request $request)
    {
        (new IDMustBePositiveInt())->goCheck();
        $blog = BlogModel::get($request->param('id'));

        if ( !$blog ){
        $blog = BlogModel::get($request->param('id'), ['user']);

        if (!$blog) {
            return _error('blog item not exists');
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
        $images  = $request->post('images/a');
        $data    = [
            'content' => $content,
            'user_id' => $this->userId,
            'images'  => $images,
        ];
        $result = BlogModel::create($data);

        return _success($result);
    }

    public function upload(Request $request)
    {
        $files    = $request->file('file');
        $fileUrl  = [];
        $flag     = true;
        
        foreach ($files as $file) {
            $info = $file
                ->validate(['ext' => 'png,jpg,jpeg,gif', 'size' => 1024 * 1024 * 5])
                ->move(UPLOAD_PATH);
            if ($info) {
                array_push($fileUrl, '/uploads' . DS . $info->getSaveName());
            } else {
                $flag = false;
                break;
            }
        }
        if ( $flag ){
            return _success($fileUrl);
        }

        return _error('upload failure');
    }
}
