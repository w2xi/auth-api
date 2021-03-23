<?php 

namespace app\api\behavior;

class CORS
{
    public function appInit(&$params)
    {
        header('Access-Control-Allow-Origin: http://10.40.2.199:8080');
        header("Access-Control-Allow-Headers: token,Origin, X-Requested-With, Content-Type, Accept, Authorization");
        header('Access-Control-Allow-Methods: POST,GET,OPTION');

        if(request()->isOptions()){
            exit();
        }
    }
}