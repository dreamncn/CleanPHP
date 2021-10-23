<?php
/*******************************************************************************
 * Copyright (c) 2021. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\lib\WebSocket\core;

use app\core\cache\Cache;
use app\core\core\Clean;
use app\core\debug\Error;
use app\core\debug\Log;
use Swoole;
class WebSocketObject
{

    static public function Run($ser,$fd,$data,$type){
        if($type=="open")return;
        //open的就不管了
        if($type=="close"){
           WebSocketSession::getInstance()->destroy();
            //关闭的时候处理
        }
        self::init();
        self::rewrite($ser,$fd,$data,$type);
        self::createObj($ser,$fd,$type);
    }
    static private function init(){
        if (isDebug()) {//调试模式不关闭错误告警
            error_reporting(-1);
            ini_set("display_errors", "On");
        } else {
            error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));
            ini_set("display_errors", "Off");
        }
    }

    /**
     * @param $ser  Swoole\WebSocket\Server
     * @param $fd
     * @param $data
     */
    static private function rewrite($ser,$fd,$data,$type){
        global $__module, $__controller, $__action,$__token,$__body;
        $__module=null;
        if($data==null)return;
        $data = trim(stripslashes($data),"\"");
        $json=json_decode($data,true);
        try{
            Log::info("websocket","原始数据：".$data);
            $__module=$json["m"];
            Log::info("websocket","json：".print_r($json,true));
            WebSocketSession::getInstance()->start($fd,$__module);
            $__controller=$json["c"];
            $__action=$json["a"];
            $__token=$json["token"];
            $__body=$json["body"];
        }catch (\Exception $e){
            $ser->close($fd);
        }

    }
    static public function createObj($ser,$fd,$type)
    {
        global $__module, $__controller, $__action,$__token,$__body;
        $__module= WebSocketSession::getInstance()->get('websocket_m');;
        if($__module==null) {
            WS::_err_router($ser,$fd,"错误: WebSocket模块 不能为 null!");
            return;
        }
        if ($__controller === 'BaseWebsocket')
            Error::_err_router("错误: 基类 'BaseWebsocket' 不允许被访问！");
        $controller_name = ucfirst($__controller);
        $action_name = $__action;
        if (!Clean::is_available_classname($__module)){
            WS::_err_router($ser,$fd,"错误: WebSocket模块 '$__module' 不正确!");
            return;
        }
        if (!is_dir(APP_DIR.DS."websocket".DS . $__module)){
            WS::_err_router($ser,$fd,"错误: WebSocket模块 '$__module' 不存在!");
            return;
        }

        $controller_name = 'app\\websocket\\' . $__module . '\\' . $controller_name;

        if (!Clean::is_available_classname($__controller))
            WS::_err_router($ser,$fd,"错误: WebSocket控制器 '$controller_name' 不正确!");

        if($type=="close"){
            $action_name="onClose";
            $controller_name="BaseWebSocket";
        }


        /**
         * @var $controller_obj WS
         */

        $controller_class_exists = class_exists($controller_name, true);

        $controller_method_exists = method_exists($controller_name, $action_name);

        if (!$controller_class_exists) {
            WS::_err_router($ser,$fd,"错误: WebSocket控制器 '$controller_name' 不存在!");
            return;
        }

        if (!$controller_method_exists ) {
            WS::_err_router($ser,$fd,"错误: WebSocket控制器 '$controller_name' 中的方法 '$action_name' 不存在!");
            return;
        }
        $controller_obj = new $controller_name($ser,$fd,$__body,$__token);
        if($type=="close"){
            $controller_obj->$action_name($fd);
            return;
        }
        $result = $controller_obj->init();
        //获取初始化结果
        if($result==null)
            $result = $controller_obj->$action_name();
        Log::info("websocket","返回结果：".$result);
        if($result!==null&&$ser->isEstablished($fd)){
            $ser->push($fd,rawurlencode($result));
        }
    }
}