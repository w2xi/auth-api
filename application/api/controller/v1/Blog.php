<?php

namespace app\api\controller\v1;

use app\api\controller\Base;
use app\api\model\Blog as BlogModel;
use app\api\model\BLogComment as BlogCommentModel;
use app\api\model\BlogLike as BlogLikeModel;
use app\api\model\BlogCommentLike as BlogCommentLikeModel;
use app\api\validate\Blog as BlogValidate;
use app\api\validate\IDMustBePositiveInt;
use think\Request;

class Blog extends Base
{
    public function search($keyword = '')
    {
        $result = (new BlogModel())->whereLike('content', "%{$keyword}%")->select();

        return _success($result);
    }

    public function commentLike(Request $request)
    {
        (new IDMustBePositiveInt())->goCheck();
        $commentId = $request->post('id');
        $comment = BlogCommentModel::get($commentId);

        if ( !$comment ){
            return _error('the comment item not exists');
        }
        $like = BlogCommentLikeModel::getItem(['user_id'=>$this->userId, 'comment_id'=>$commentId]);
        if ( $like ){
            $like->like ? $comment->like_count-- : $comment->like_count++;
            $like->like = !$like->like;
            $like->save();
        }else{
            $like = BlogCommentLikeModel::create([
                'comment_id'    =>  $comment->id,
                'user_id'       =>  $this->userId,
                'like'          =>  1,
            ]);
            $comment->like_count++;
        }
        $comment->save();

        return _success($like);
    }

    public function replyList(Request $request)
    {
        $page = 1;
        $showReplyCountByDefault = 2;
        $commentId = $request->param('id');
        $comment = BlogCommentModel::get($commentId);

        if ( !$comment ){
            return _error('comment item not exists');
        }
        $res = BlogCommentModel::replyList($commentId, $page, $showReplyCountByDefault);

        return _success($res);
    }

    public function reply(Request $request)
    {
        (new BlogValidate)->scene('reply')->goCheck();
        $blogId = $request->post('id');
        $toUserId = $request->post('uid');
        $commentId = $request->post('cid');
        $content = $request->post('content');
        $blog    = BlogModel::get($blogId);

        if (!$blog) {
            return _error('the blog item not exists');
        }
        $blogComment = BlogCommentModel::get($commentId);

        if ( !$blogComment ){
            return _error('the comment item not exists');
        }
        $res = BlogCommentModel::create([
            'blog_id'   =>  $blog->id,
            'pid'       =>  $blogComment->id,
            'user_id'   =>  $this->userId,
            'to_user_id'=>  $toUserId,
            'content'   =>  $content,
        ]);

        return _success($res);
    }

    public function comment(Request $request)
    {
        (new BlogValidate)->scene('comment')->goCheck();
        $blogId  = $request->post('id');
        $content = $request->post('content', 'htmlspecialchars');
        $blog    = BlogModel::get($blogId);

        if (!$blog) {
            return _error('the blog item not exists');
        }
        $comment = BlogCommentModel::create([
            'blog_id' => $blogId,
            'user_id' => $this->userId,
            'content' => $content,
        ]);

        return _success($comment);
    }

    public function commentList(Request $request)
    {   
        (new IDMustBePositiveInt)->goCheck();
        $blogId = $request->param('id');
        $blog = BlogModel::get($blogId);

        if ( !$blog ){
            return _error('blog item not exists');
        } 
        $commentList = BlogCommentModel::getList($page, $count, $blogId);
    }

    public function like(Request $request)
    {
        (new IDMustBePositiveInt())->goCheck();
        $blogId = $request->post('id/d');
        $blog   = BlogModel::get($blogId);

        if (!$blog) {
            return _error('the blog item not exists');
        }
        $blogLike = BlogLikeModel::like(['blog_id' => $blogId, 'user_id' => $this->userId]);

        if ( $blogLike ) {
            $blogLike->like ? $blog->like_count-- : $blog->like_count++;
            $blogLike->like = !$blogLike->like;
            $blogLike->save();
        } else {
            $blogLike = BlogLikeModel::create([
                'blog_id' => $blogId,
                'user_id' => $this->userId,
                'like'    => 1, 
            ]);
            $blog->like_count++;
        }
        $blog->save();

        return _success($blogLike);
    }

    public function detail(Request $request)
    {
        (new IDMustBePositiveInt())->goCheck();
        $blog = BlogModel::get($request->param('id'), ['user']);

        if (!$blog) {
            return _error('blog item not exists');
        }
        return _success($blog);
    }

    public function read(Request $request)
    {
        $page   = $request->param('page', 1);
        $count  = $request->param('count', 10);
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
        $files   = $request->file('file');
        $fileUrl = [];
        $flag    = true;

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
        if ($flag) {
            return _success($fileUrl);
        }

        return _error('upload failure');
    }
}
