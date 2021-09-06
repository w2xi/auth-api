<?php 

namespace app\api\validate;

class UserValidate extends BaseValidate
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