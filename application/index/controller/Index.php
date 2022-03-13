<?php


namespace app\index\controller;


use app\api\model\User;
use think\Request;

class Index
{
    public function jsonp()
    {
        return jsonp(config());
    }

    public function imagePings()
    {
        return 'image pings';
    }

    public function unauth()
    {
        return json(config(), 401);
    }

    public function profile(Request $request)
    {
        cookie('test', 'val');

        $username = $request->param('username', 'w2xi');

        $user = new User();

        return json($user->where('username', $username)->find());
    }

    public function test()
    {
        return 1212;
    }
}