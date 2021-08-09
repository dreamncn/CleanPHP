<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\controller\index;

use app\core\mvc\Controller;

class BaseController extends Controller
{

    function init()
    {
        header("Content-type: text/html; charset=utf-8");
        $this->setLayout("layout");
    }

    //public static function err404(){}
    //public static function err500(){}
}
