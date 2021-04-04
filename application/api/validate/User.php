<?php 

namespace app\api\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
        'username'  =>  'require|isNotEmpty',
        'avatar'    =>  'require',
        'bio'       =>  'require',
    ];

    protected $scene = [
        'edit'      =>  [ 'username' ],
        'login'     =>  [ 'username', 'password' ],
        'register'  =>  [ 'username', 'password', 'ackPassword' ],
    ];
}