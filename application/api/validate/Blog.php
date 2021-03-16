<?php 

namespace app\api\validate;

class Blog extends BaseValidate
{
    protected $rule = [
        'id'        =>  'requrie|isPositiveInteger',
        'content'   =>  'require|isNotEmpty',
    ];

    protected $scene = [
        'comment'   =>  [ 'id', 'content' ],
    ];
}