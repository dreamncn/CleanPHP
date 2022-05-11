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
        if($roleData==null)return null;
        $json = $roleData["auth"];

        foreach ($json as $item){
            if($item=="all")return null;
            if($this->check("{$msg['m']}",$item)) return null;
            if($this->check("{$msg['m']}/{$msg['c']}",$item)) return null;
            if($this->check("{$msg['m']}/{$msg['c']}/{$msg['a']}",$item)) return null;
        }

       if(!isDebug()){
           Response::msg(true,403,"403 Forbidden","对不起您没有访问权限！",10,"/");
       }else{
          dumpAll("当前为调试模式","对不起您没有访问权限","权限信息",$roleData);
       }

       return null;
    }
    private function check($url,$item): bool
    {
        if($item==$url)return true;
        if(StringUtil::get($item)->contains($url."?")){
            parse_str(StringUtil::get($item)->findStart("?"),$array);
            $allow = false;
            foreach ($array as $key => $value){
                if(arg($key)==$value){
                    $allow = true;
                }
            }
            if($allow)return true;
        }
        return false;
    }
}
