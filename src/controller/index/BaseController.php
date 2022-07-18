<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace controller\index;

use core\mvc\Controller;
use core\web\Session;

class BaseController extends Controller
{

    public function init()
    {
        Session::getInstance()->start();
        return parent::init();
    }

    //public static function err404($__module, $__controller, $__action, $message){}
    //public static function err500($__module, $__controller, $__action, $message){}
}
