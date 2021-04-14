<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
// 定义错误日志目录
define('LOG_PATH', __DIR__ . '/../log/');
// 定义上传文件目录
define('UPLOAD_PATH', __DIR__ . '/uploads/');

if ( !function_exists('dd') )
{
    function dd($data, $exit = false, $type = 'text')
    {
        if ( is_array($data) ){
            if ( $type === 'json' ){
                echo json_encode($data);
            }else{
                echo '<pre>';
                print_r($data);
            }
        }else if ( is_null($data) || is_bool($data) ){
            var_dump($data);
            echo '<br />';
        }else{
            print_r($data);
            echo '<br />';
        }

        if ( $exit ){
            exit();
        }
    }
}

// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
