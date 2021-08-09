<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\controller\hide;

use app\core\mvc\Controller;
use app\core\web\Response;

class BaseController extends Controller
{

    function init()
    {
        header("Content-type: text/html; charset=utf-8");
        $this->checkUser();
    }

    //此处可以检查权限
    private function checkUser(){
        if(!arg("isAuth",false,true,"bool"))
        Response::msg(true,403,"没有操作权限","您没有访问权限",3,url("index","main","index"));
    }
    //public static function err404(){}
    //public static function err500(){}
}
