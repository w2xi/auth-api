<?php 

namespace app\api\model;

use think\Model;

class BlogCommentLike extends Model 
{
    public static function getItem($where = [])
    {
        return self::where($where)->find();
    }
}