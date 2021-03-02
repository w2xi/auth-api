<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

if ( !function_exists('__success') )
{
    function __success($data = null, $msg = 'success')
    {
        $result = [
            'code'  =>  10000,
            'msg'   =>  $msg,
            'data'  =>  $data,
        ];

        return json($result);
    }
}

if ( !function_exists('__error') )
{
    function __error($msg = 'failure', $data = null)
    {
        $result = [
            'code'  =>  10001,
            'msg'   =>  $msg,
            'data'  =>  $data,
        ];

        return json($result);
    }
}

if ( !function_exists('genRandomChar') )
{
    // generate random string of given length
    function genRandomChar($length = 6)
    {
        $str = '';
        $chars = '123456789abcdefghjkmnpqrstuvwxyABCDEFGHJKLMNPQRSTUVWXY';

        for ( $i = 0; $i < $length; $i++ ){
            $str .= $chars[mt_rand(0, strlen($chars) - 1)];
        }

        return $str;
    }
}

