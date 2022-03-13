<?php

namespace app\api\controller\v1;

use app\api\exception\JwtTokenMissingException;
use app\api\exception\NeedPermissionException;
use app\api\service\JwtAuth;
use app\api\model\User as UserModel;

class Token
{
    public function refresh()
    {
        $token = request()->header('Authorization');
        if (!$token) {
            throw new JwtTokenMissingException();
        }
        $instance = JwtAuth::instance();
        $instance->setToken($token)->refreshValidate();
        $user = UserModel::get($instance->getUid());
        if (!$user) {
            throw new NeedPermissionException();
        }
        // issue token
        $token = $instance->encode()->getToken();

        return _success(['token' => $token]);
    }

    public function create()
    {
        $uid = 1;
        $instance = JwtAuth::instance();
        // issue token
        $token      = $instance->setUid($uid)->encode()->getToken();
        $expireTime = $instance->getExpireTime()->getTimestamp();

        return _success(['token' => $token]);
    }
}
