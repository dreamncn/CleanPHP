<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace extend\rbac\core;


use core\config\Config;

/**
 * Class Tasker
 * @package extend\net_ankio_tasker\core
 * Date: 2020/12/23 23:46
 * Author: ankio
 * Description: 定时任务管理器
 */

class Role
{

    protected static ?Role $instance= null;

    private $config;
    public function __construct($rbac)
    {
        $this->config = $rbac;
    }

    public function __destruct()
    {
        Config::getInstance("rbac")->setLocation(EXTEND_RBAC."data".DS)->setAll($this->config);
    }

    public static function getInstance()
    {

        if(!isset(self::$instance)||self::$instance==null) {
            self::$instance  = new Role(Config::getInstance("rbac")->setLocation(EXTEND_RBAC."data".DS)->getAll());
        }

        return self::$instance;

    }

    public function add($name,$auth){
        $id = sizeof( $this->config);
        while (isset( $this->config["id_".$id]))$id++;

        $this->config["id_".$id]=[
            "role_name"=>$name,
            "auth"=>$auth
        ];
        return $id;
    }

    public function udp($id,$auth){
        if(isset($this->config[$id])){
            $this->config["id_".$id]=[
                "role_name"=>$this->config["id_".$id]["role_name"],
                "auth"=>$auth
            ];
        }
    }

    public function del($id){
        if(isset($this->config["id_".$id])){
            unset($this->config["id_".$id]);
        }
    }

    public function list(){
        return $this->config;
    }

    public function get($id){
        if(isset($this->config["id_".$id])){
            return $this->config["id_".$id];
        }
        return null;
    }

    /**
     * @param $m string 模块
     * @param $c string 控制器
     * @return array
     */
    public function getApi(string $m, string $c): array
    {
        $name = "controller\\$m\\$c";
        $methods = get_class_methods($name);
        $methods2 = get_class_methods("controller\\$m\\BaseController");
        foreach ($methods as $key => $val) {
            if (in_array($val,$methods2)) {
                unset($methods[$key]);
            }else{
                $methods[$key]="$m/$c/$val";
            }
        }
        return $methods;
    }

}