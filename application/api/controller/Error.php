<?php


namespace app\api\controller;


use think\Controller;
use think\Exception;

class Error extends Controller
{
    public function index()
    {
        throw new Exception('Module, Controller or Action not exists');
    }
}