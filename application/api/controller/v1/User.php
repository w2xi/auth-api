<?php

namespace app\api\controller\v1;

use app\api\controller\Base;
use app\api\model\ApiToken as ApiTokenModel;
use app\api\model\User as UserModel;
use app\api\service\JwtAuth;
use think\Request;
use app\api\validate\UserValidate;

class User extends Base
{
    protected $noNeedLogin = ['login', 'register'];

    protected $errorMsg = '';

    public function register(Request $request)
    {
        (new UserValidate())->scene('register')->goCheck();
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
        (new UserValidate())->scene('login')->goCheck();
        $username = $request->param('username');
        $password = $request->param('password');

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
        $data = [
            'user_id'       =>  $user->id,
            'token'         =>  $token,
            'expire_time'   =>  $expireTime,
            'userInfo'      =>  $user,
        ];
        if ($apiToken) {
            $apiToken->token       = $token;
            $apiToken->expire_time = $expireTime;
            $apiToken->save();
        } else {
            ApiTokenModel::create($data);
        }

        return _success($data);
    }

    public function profile(Request $request)
    {
        $userInfo = UserModel::get($this->userId);

        return _success($userInfo);
    }

    public function edit(Request $request)
    {
        (new UserValidate())->scene('edit')->goCheck();
        $data = [
            'username'  =>  $request->post('nickname'),
            'bio'       =>  $request->post('bio'),
        ];
        $file = $request->file('file');

        if ( $file ){
            $info = $file->validate(['size'=>1024*1024*5, 'ext'=>'png,jpg,jpeg'])->move(UPLOAD_PATH.'avatar');
            if ( $info ){
                $data['avatar'] = '/uploads/avatar'.$info->getSaveName();
            }else{
                return _error($info->getError());
            }
        }
        Db::startTrans();
        try{
            $user = UserModel::get($this->userId);
            $user->save($data, ['id'=>$this->userId]);
            Db::commit();
        }catch(\Exception $e){
            Db::rollback();
            return _error('edit failed');
        }

        return _success('edit successfully');
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
