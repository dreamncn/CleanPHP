<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\extend\rbac\core;

use app\core\debug\Log;
use app\core\web\Response;
use app\core\web\Route;


/**
 * Class Tasker
 * @package app\extend\net_ankio_tasker\core
 * Date: 2020/12/23 23:46
 * Author: ankio
 * Description: 定时任务管理器
 */

class Role extends Db
{
    
    public static string $table = "rbac_role";
    

    public function setDb()
    {
        parent::setDb();

        $this->execute("CREATE TABLE IF NOT EXISTS rbac_role( id integer PRIMARY KEY AUTOINCREMENT , role_name text,auth TEXT)");
    }

    public function add($name,$auth){
        $this->insert()->keyValue(["role_name"=>$name,"auth"=>$auth])->commit();
    }

    public function udp($id,$auth){
        $this->update()->set(["auth"=>$auth])->where(["id"=>$id])->commit();
    }

    public function del($id){
        $this->delete()->where(["id"=>$id])->commit();
    }

    public function list(){
        return $this->select()->commit();
    }

    public function get($id){
        $data = $this->select()->where(["id"=>$id])->commit();
        if(empty($data))return [
            "id"=>-1,"role_name"=>"admin","auth"=>"all"
        ];
        else return $data[0];
    }

    /**
     * @param $m string 模块
     * @param $c string 控制器
     * @return array
     */
    public function getApi(string $m, string $c): array
    {
        $name = "app\\controller\\$m\\$c";
        if(class_exists($name)){
            return  get_class_methods("app\\controller\\$m\\$c");
        }
        return [];
    }

}