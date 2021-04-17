<?php
/*******************************************************************************
 * Copyright (c) 2021. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\vendor\core;

use app\vendor\config\Config;
use app\vendor\debug\Error;
use app\vendor\debug\Log;
use app\vendor\event\EventManager;
use app\vendor\mvc\Controller;
use app\vendor\release\FileCheck;
use app\vendor\release\Release;
use app\vendor\web\Route;


/**
 * +----------------------------------------------------------
 * Class Clean
 * +----------------------------------------------------------
 * @package app\vendor\core
 * +----------------------------------------------------------
 * Date: 2020/11/21 11:01 下午
 * Author: ankio
 * +----------------------------------------------------------
 * Desciption:框架启动
 * +----------------------------------------------------------
 */
class Clean
{


    /**
     * +----------------------------------------------------------
     * 启动
     * +----------------------------------------------------------
     */
    static public function Run()
    {
        //框架开始类
        self::Init();
        Route::rewrite();
        self::createObj();

    }

    static private function Console(){
        if(isset($_SERVER['CLEAN_CONSOLE'])&&$_SERVER['CLEAN_CONSOLE']){
            if ($_SERVER["REQUEST_URI"] == "clean_check") {
                FileCheck::run();
                exitApp("命令行执行完毕");
            } else if ($_SERVER["REQUEST_URI"] == "clean_release") {
                Release::run();
                exitApp("命令行执行完毕");
            } else if ($_SERVER["REQUEST_URI"] == "clean_clean") {
                Release::clean();
                exitApp("命令行执行完毕");
            }


        }
    }
    /**
     * +----------------------------------------------------------
     * 初始化数据
     * +----------------------------------------------------------
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
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
        if (in_array(str_replace($GLOBALS['http_scheme'], '', $origin), $GLOBALS["frame"]['host'])) {
            header('Access-Control-Allow-Origin:' . $origin);
        }

        self::Console();//命令行执行

        EventManager::fire("afterFrameInit", null);

        //完整性校验
        if(!isDebug()&&Config::getInstance("frame")->setLocation(APP_CONF)->getOne("check")){

            if(!FileCheck::checkMd5(APP_DIR,Config::getInstance("frame")->setLocation(APP_CONF)->getOne("md5"))){
                exitApp("应用程序完整性检查校验未通过。");
            }
        }

    }

    /**
     * +----------------------------------------------------------
     *
     * +----------------------------------------------------------
     */
    static public function createObj()
    {
        global $__module, $__controller, $__action;
        if ($__controller === 'BaseController') Error::_err_router("错误: 基类 'BaseController' 不允许被访问！");

        $controller_name = ucfirst($__controller);
        $action_name = $__action;
        Log::debug('clean', "[MVC] $__module/$controller_name/$action_name");
        Log::debug('mvc', '模块: ' . $__module);
        Log::debug('mvc', '控制器: ' . $controller_name);
        Log::debug('mvc', '方法: ' . $action_name);
        Log::debug('clean', '路由耗时: ' . strval((microtime(true) - $GLOBALS['frame_start']) * 1000) . 'ms');

        if (!self::is_available_classname($__module)) Error::_err_router("错误: 模块 '$__module' 不正确!");


        if (!is_dir(APP_CONTROLLER . $__module)) Error::_err_router("错误: 模块 '$__module' 不存在!");

        $controller_name = 'app\\controller\\' . $__module . '\\' . $controller_name;


        if (!self::is_available_classname($__controller))
            Error::_err_router("错误: 控制器 '$controller_name' 不正确!");

        /**
         * @var $controller_obj Controller
         */

        $auto_tpl_name = $__controller . '_' . $__action;

        $auto_tpl_file_exists = file_exists(APP_VIEW . $__module . DS . $auto_tpl_name . '.tpl');
        $controller_class_exists = class_exists($controller_name, true);

        $controller_method_exists = method_exists($controller_name, $action_name);

        if (!$controller_class_exists && !$auto_tpl_file_exists) {
            Error::_err_router("错误: 控制器 '$controller_name' 不存在!");
        }

        if (!$controller_method_exists && !$auto_tpl_file_exists) {
            Error::_err_router("错误: 控制器 '$controller_name' 中的方法 '$action_name' 不存在!");
        }
        $result = null;
        if ($controller_class_exists && $controller_method_exists) {
            $controller_obj = new $controller_name();

            $result = $controller_obj->$action_name();
            if ($controller_obj->_auto_display) {

                if ($auto_tpl_file_exists) {
                    Log::debug('clean', '自动输出模板 '.$auto_tpl_name);
                    $result =  $controller_obj->display($auto_tpl_name);
                }
            }

        } else {
            $base='app\\controller\\' . $__module . '\\BaseController';
            $controller_obj = new $base();
            if ($auto_tpl_file_exists) {
                Log::debug('clean', '无方法输出模板 '.$auto_tpl_name);
                $result = $controller_obj->display($auto_tpl_name);

            }

        }
        if($result!=null){
            if(is_array($result)){
                header('content-type:application/json');
                echo json_encode($result);
            }else if($controller_obj->isEncode()){
                echo htmlspecialchars($result,ENT_QUOTES,"UTF-8",true);
            }else{
                echo $result;
            }
        }
        //输出html
        Log::debug('Clean', '框架运行完成，总耗时: ' . strval((microtime(true) - $GLOBALS['frame_start']) * 1000) . 'ms');

    }


    static public function is_available_classname($name)
    {
        return preg_match('/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/', $name);
    }


}
