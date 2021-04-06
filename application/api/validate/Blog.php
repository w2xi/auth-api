<?php 

namespace app\api\validate;

class Blog extends BaseValidate
{
    protected $rule = [
        'id'        =>  'requrie|isPositiveInteger',
        'cid'       =>  'require|isPositiveInteger',
        'uid'       =>  'require|isPositiveInteger',
        'content'   =>  'require|isNotEmpty',
    ];

    protected $scene = [
        'comment'   =>  [ 'id', 'content' ],
        'reply'     =>  [ 'id', 'cid', 'uid', 'content' ],
    ];
}