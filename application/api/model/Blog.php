<?php 

namespace app\api\model;

use think\Model;

class Blog extends BaseModel
{
    public function user()
    {
      return $this->belongsTo('User', 'user_id', 'id')->field('id,username,avatar');
    }

    public function getImagesAttr($value)
    {
        if (empty($value)) return [];

        $img = json_decode($value, true);
        return $img ?: [];
    }

    public function setImagesAttr($value)
    {
        if ( !$value ){
            return $value;
        }
        $img = is_array($value) ? $value : json_decode($value, true);
        $img = $img ?: [];

        return json_encode($img);
    }

    public static function getList($page, $count, $where = [])
        {
        return self::with('user')->where($where)->order('id desc')->page($page, $count)->select();
        }
    }