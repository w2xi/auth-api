<?php

namespace app\api\controller;

use app\api\exception\JwtTokenMissingException;
use app\service\JwtAuth;

class Base
{
    /**
     * @var int
     */
    protected $userId;
    /**
     * no need to login, meaning no need to authenticate
     * @var array
     */
    protected $noNeedLogin = [];

    public function __construct()
    {
        if (!$this->match($this->noNeedLogin)) {
            $this->checkToken();
            return;
        }
        // if current api action mathchd by match method with noNeedLogin prop and token in request headers.
        if (request()->header('token')) {
            // todo#1
        }
    }

    public function checkToken()
    {
        $token = request()->header('token');
        if (!$token) {
            throw new JwtTokenMissingException();
        }
        $instance = JwtAuth::instance();
        $instance->setToken($token)->verify();
        $user = UserModel::get($instance->getUid());
        if (!$user) {
            throw new NeedPermissionException();
        }
        $this->userId = $user->id;
    }

    public function match($arr = [])
    {
        $arr    = is_array($arr) ? $arr : explode(',', $arr);
        if (!$arr) {
            return fasle;
        }
        $arr    = array_map('strtolower', $arr);

        if (in_array(strtolower(request()->action()), $arr) || in_array('*', $arr)) {
            return true;
        }

        return fasle;
    }
}
