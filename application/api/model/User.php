<?php 

namespace app\api\model;

use think\Model;

class User extends Model 
{
	protected $hidden = ['password', 'salt', 'ip'];
}