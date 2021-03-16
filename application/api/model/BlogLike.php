<?php 

namespace app\api\model;


class BlogLike extends BaseModel
{
    public static function like($where = [])
    {
        return self::where($where)->find();
    }
}