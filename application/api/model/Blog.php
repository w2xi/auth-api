<?php 

namespace app\api\model;

use think\Model;

class Blog extends Model
{
	protected $autoWriteTimestamp = true;
	// 定义时间戳字段名
  protected $createTime = 'create_at';
  protected $updateTime = false;

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

  public static function getList($page, $count)
  {
  	return self::with('user')->page($page, $count)->select();
  }
}