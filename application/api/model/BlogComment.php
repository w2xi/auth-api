<?php 

namespace app\api\model;

use think\Model;

class BlogComment extends Model
{
    public static function replyList($commentId, $page, $count)
    {
        return self::where('pid', $commentId)->page($page, $count)->select()->toArray();
    }

    public static function getList($page, $count, $blogId)
    {
        return self::where('blog_id', $blog_id)->page($page, $count)->select()->toArray();
    }
}