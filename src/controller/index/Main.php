<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\controller\index;


use app\core\database\Sql;
use app\core\mvc\Model;
use app\core\web\Response;
use app\core\web\Session;
use app\extend\ankioTask\core\Tasker;
use app\extend\rbac\core\RBAC;
use app\extend\rbac\core\Role;



class Main extends BaseController
{


    public function index()
	{

         Response::msg(false,200,"CleanPHP","Welcome to use CleanPHP",-1,"https://github.com/dreamncn/CleanPHP","Github");
    }

}
