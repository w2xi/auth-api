<?php

namespace app\api\controller\v1;

use app\api\controller\Base;
use app\api\model\ApiToken as ApiTokenModel;
use app\api\model\User as UserModel;
use app\api\service\JwtAuth;
use think\Request;

class User extends Base
{
    protected $noNeedLogin = ['login', 'register'];

    protected $errorMsg = '';

    public function register(Request $request)
    {
        $username       = $request->post('username');
        $password       = $request->post('password');
        $ackPassword    = $request->post('ackPassword');
        $salt           = genRandomChar(6);
        $cryptoPassword = md5(md5($password) . $salt);

        $user = (new UserModel())->where('username', $username)->find();
        if ($user) {
            return _error('User already exists');
        }
        if ($password !== $ackPassword) {
            return _error('Please confirm your password');
        }

        UserModel::create([
            'username'  => $username,
            'salt'      => $salt,
            'password'  => $cryptoPassword,
            'create_at' => time(),
            'ip'        => $request->ip(),
        ]);

        return _success();
    }
    public function login(Request $request)
    {
        $username = $request->post('username');
        $password = $request->post('password');

        $user = (new UserModel())->where('username', $username)->find();
        if (!$user) {
            return _error('User not exists');
        }
        // verify password
        if (!$this->verifyPassword($password, $user)) {
            return _error($this->errorMsg);
        }
        $apiToken = (new ApiTokenModel())->where('user_id', $user->id)->find();
        $instance = JwtAuth::instance();
        // issue token
        $token      = $instance->setUid($user->id)->encode()->getToken();
        $expireTime = $instance->getExpireTime()->getTimestamp();
        if ($apiToken) {
            $apiToken->token       = $token;
            $apiToken->expire_time = $expireTime;
            $apiToken->save();
        } else {
            ApiTokenModel::create([
                'user_id'     => $user->id,
                'token'       => $token,
                'expire_time' => $expireTime,
            ]);
        }

        return _success([
            'user_id'     => $user->id,
            'token'       => $token,
            'expire_time' => $expireTime,
        ]);
    }

    public function profile(Request $request)
    {
        $userInfo = UserModel::get($this->userId);

        return _success($userInfo);
    }

    private function verifyPassword($password, $userInfo)
    {
        $cryptoPassword = md5(md5($password) . $userInfo->salt);
        if ($cryptoPassword !== $userInfo->password) {
            $this->errorMsg = 'Password not valid';
            return false;
        }
        return true;
    }
}
