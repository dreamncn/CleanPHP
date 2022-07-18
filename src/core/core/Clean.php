<?php
/*******************************************************************************
 * Copyright (c) 2021. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace core\core;

use core\debug\Debug;
use core\debug\Log;
use core\error\RouteError;
use core\event\EventManager;
use core\mvc\Controller;
use core\release\FileCheck;
use core\release\Release;
use core\web\Request;
use core\web\Route;


/**
 * Class Clean
 * @package core\core
 * Date: 2020/11/21 11:01 下午
 * Author: ankio
 * Description:框架启动
 */
class Clean
{

    /**
     * 启动
     */
    static public function Run()
    {
        //框架开始类
        self::Init();
        Log::debug("frame_run","框架初始化完毕");
        Route::rewrite();
        Log::debug("frame_run","路由完毕");
        self::createObj();
    }

    public static function isConsole(): bool
    {
        return isset($_SERVER['CLEAN_CONSOLE'])&&$_SERVER['CLEAN_CONSOLE'];
    }


    /**
     * 初始化数据
     */
    static public function Init()
    {
        if (isDebug()) {//调试模式不关闭错误告警
            error_reporting(-1);
            ini_set("display_errors", "On");
        } else {
            error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));
            ini_set("display_errors", "Off");
        }
        //识别ssl
        if ((!empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == "https") || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") || (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) {
            $GLOBALS['http_scheme'] = 'https://';
        } else {
            $GLOBALS['http_scheme'] = 'http://';
        }
        //允许跨域
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        if (in_array(str_replace($GLOBALS['http_scheme'], '', $origin), $GLOBALS["frame"]['host'])) {
            @header('Access-Control-Allow-Origin:' . $origin);
        }
        //判断是否为命令行执行
        if(!self::isConsole())
            EventManager::fire("onFrameInit");

    }

    /**
     * 根据识别到的url创建对象
     */
    static public function createObj()
    {
        global $__module, $__controller, $__action;
        if (strtolower($__controller) === 'basecontroller')
            new RouteError("错误: 基类 'BaseController' 不允许被访问！");

        $controller_name = ucfirst($__controller);
        $action_name = $__action;
        Log::debug("frame_run","响应controller：$__module/$__controller/$__action");

        if (!self::isAvailableClassname($__module)) new RouteError("错误: 模块 '$__module' 命名不符合规范!");


        if (!is_dir(APP_CONTROLLER . $__module)){
            new RouteError("错误: 模块 '$__module' 不存在!");
        }

        $controller_name = 'controller\\' . $__module . '\\' . $controller_name;


        Log::debug("frame_run","创建controller对象：".$controller_name);


        if (!self::isAvailableClassname($__controller))
            new RouteError("错误: 控制器 '$controller_name' 命名不符合规范!");

        /**
         * @var $controller_obj Controller
         */

        $auto_tpl_name = $__controller . '_' . $__action;

        $auto_tpl_file_exists = file_exists(APP_VIEW . $__module . DS . $auto_tpl_name . '.tpl');
        $controller_class_exists = class_exists($controller_name, true);

        $controller_method_exists = method_exists($controller_name, $action_name);

        if (!$controller_class_exists && !$auto_tpl_file_exists) {
            new RouteError("错误: 控制器 '$controller_name' 不存在!");
        }

        if (!$controller_method_exists && !$auto_tpl_file_exists) {
            new RouteError("错误: 控制器 '$controller_name' 中的方法 '$action_name' 不存在!");
        }


        if(!in_array_case($action_name,get_class_methods($controller_name))){
            new RouteError("错误: 控制器 '$controller_name' 中的方法 '$action_name' 为私有方法，禁止访问!");
        }


        $result = null;
        if ($controller_class_exists && $controller_method_exists) {
            $controller_obj = new $controller_name();
            $result = $controller_obj->getInit();
            //获取初始化结果
            if($result==null)
                $result = $controller_obj->$action_name();
            //初始化如果有输出则直接输出，不执行函数。
            if ($controller_obj->_auto_display) {

                if ($auto_tpl_file_exists) {
                  $result =  $controller_obj->display($auto_tpl_name);
                }
            }

        } else {
            $base='controller\\' . $__module . '\\BaseController';
            $controller_obj = new $base();
            if ($auto_tpl_file_exists) {
                $result = $controller_obj->display($auto_tpl_name);
            }

        }
        if($result!=null){
            if(is_array($result)){
                Log::debug("frame_run","输出JSON数据");
                @header('content-type:application/json');
                echo json_encode($result);
            }else if($controller_obj->isEncode()){
                Log::debug("frame_run","编码输出html");
                echo htmlspecialchars($result,ENT_QUOTES,"UTF-8",true);
            }else{
                Log::debug("frame_run","原样输出html");
                echo $result;
            }
        }
        exitApp("框架执行完毕，退出。");
        //输出html
    }
    /**
     * 判断是否为标准class
     * @param string $name
     * @return false|int
     */
    static public function isAvailableClassname(string $name)
    {
        return preg_match('/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/', $name);
    }

}
