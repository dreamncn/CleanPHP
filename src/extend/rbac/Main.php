<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\extend\rbac;


use app\core\event\EventListener;
use app\core\utils\StringUtil;
use app\core\web\Response;
use app\extend\rbac\core\RBAC;
use app\extend\rbac\core\Role;

define("EXTEND_RBAC",APP_EXTEND."rbac".DS);

class Main implements EventListener
{
    /**
     * @param string $event
     * @param array|string $msg
     * @return string 实现接口
     */
    public function handleEvent(string $event, $msg): ?string
    {


        $roleData =   Role::getInstance()->get(RBAC::getRole());

       if( $roleData["auth"] == "all") return null;
        $url = "{$msg['m']}/{$msg['c']}/{$msg['a']}";
       if(StringUtil::get($roleData["auth"])->contains($url)) return null;
        $url = "{$msg['m']}/{$msg['c']}/*";
        if(StringUtil::get($roleData["auth"])->contains($url)) return null;
        $url = "{$msg['m']}/*";
        if(StringUtil::get($roleData["auth"])->contains($url)) return null;

       Response::msg(true,403,"403 Forbidden","对不起您没有访问权限！",10,"/");

       return "";
    }
}
