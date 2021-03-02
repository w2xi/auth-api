<?php

namespace app\api\controller\v1;

use app\api\controller\Auth;
use app\api\model\ApiToken as ApiTokenModel;
use app\api\model\User as UserModel;
use app\api\service\JwtAuth;
use think\Request;

class User extends Auth
{
    public function register(Request $request)
    {
        $username       = $request->post('username');
        $password       = $request->post('password');
        $salt           = genRandomChar(6);
        $cryptoPassword = md5(md5($password) . $salt);

        UserModel::create([
            'username'  => $username,
            'salt'      => $salt,
            'password'  => $cryptoPassword,
            'create_at' => time(),
            'ip'        => $request->ip(),
        ]);

        return __success();
    }

    public function login(Request $request)
    {
        $username = $request->post('username');
        $password = $request->post('password');

        $userInfo = (new UserModel())->where('username', $username)->find();
        if (!$userInfo) {
            return __error('user is not exists');
        }
        // verify password
        $cryptoPassword = md5(md5($password) . $userInfo->salt);
        if ($cryptoPassword !== $userInfo->password) {
            return __error('your password is not valid');
        }
        $apiToken = (new ApiTokenModel())->where('user_id', $userInfo->id)->find();
        if ($apiToken) {
            return __success(['token' => $apiToken->token]);
        }
        // issue token
        $token = JwtAuth::instance()->setUid($userInfo->id)->encode()->getToken();
        ApiTokenModel::create([
            'user_id'   => $userInfo->id,
            'token'     => $token,
            'expire_in' => JwtAuth::instance()->getExpireTime(),
        ]);

        return __success(['token' => $token]);
    }

    public function profile(Request $request)
    {
        $token = $request->header('token');
        JwtAuth::instance()->setToken($token)->verify();

        $userInfo = UserModel::get(JwtAuth::instance()->getUid());

        return __success($userInfo);
    }

}
