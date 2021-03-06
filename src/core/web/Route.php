<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace core\web;

use core\cache\Cache;
use core\debug\Log;
use core\error\RouteError;
use core\utils\StringUtil;


/**
 * Class Route
 * @package core\web
 * Date: 2020/11/22 11:24 下午
 * Author: ankio
 * Description:路由类
 */
class Route
{


    /**
     * 路由URL生成
     * @param string $m 模块
     * @param string $c 控制器
     * @param string $a 执行方法
     * @param array $params 附加参数
     * @return string
     */
    public static function url(string $m, string $c, string $a, array $params = []): string
    {
        $isRewrite=$GLOBALS["frame"]["rewrite"];


        $paramsStr = empty($params) ? '' : '?' . http_build_query($params);
        $route = "$m/$c/$a";
        $url = Response::getAddress() . "/";
        if(!$isRewrite) $url.="?";
        $default = $url . $route ;
        $default = strtolower($default). $paramsStr;
       $instance =  Cache::init(365 * 24 * 60 * 60, APP_ROUTE);
        //初始化路由缓存，不区分大小写
        $data = "";
        if (!isDebug())
            $data =$instance->get('route_' . $default);
        if ($data !== "") {
            return $data;
        }


        $arr = str_replace("<m>", $m, $GLOBALS['route']);
        $arr = str_replace("<c>", $c, $arr);
        $arr = str_replace("<a>", $a, $arr);
        $arr = array_flip(array_unique($arr));

        $route_find = $route;
        if (isset($arr[$route])) {


            //处理参数部分
            $route_find = $arr[$route];
            $route_find = str_replace("<m>", $m, $route_find);
            $route_find = str_replace("<c>", $c, $route_find);
            $route_find = str_replace("<a>", $a, $route_find);



            foreach ($params as $key => $val) {
                if (strpos($route_find, "<$key>") !== false) {
                    $route_find = str_replace("<$key>", $val, $route_find);
                    unset($params[$key]);
                }

            }
        }




        if ($route_find == $route || strpos($route_find, '<') !== false) {
            $retUrl = $default;
        } else {
            $paramsStr = empty($params) ? '' : '?' . http_build_query($params);
            $retUrl = $url . $route_find . $paramsStr;
        }
        if (!isDebug())
            $instance->set('route_' . $default, $retUrl);

        if(strrpos($retUrl,"?")===strlen($retUrl)-1){
            return substr($retUrl,0,strlen($retUrl)-1);
        }
        return $retUrl;

    }

    /**
     * 路由重写
     */
    public static function rewrite()
    {
        $GLOBALS['route_start']=microtime(true);
        Log::debug("frame_run","路由开始");
        $isRewrite=$GLOBALS["frame"]["rewrite"];

        if(!$isRewrite){
            $query = $_SERVER["QUERY_STRING"];
            if($query!==""){
                if(($len = strpos($query,"?"))!==false){

                    $query = substr($query,0,$len);
                  //  dumpAll($query,$len);
                    $realQuery = str_replace("$query?","",$_SERVER["QUERY_STRING"]);
                    parse_str($realQuery,$result);
                    $_GET = $result;
                    foreach ($result as $item => $value){
                        $_REQUEST[$item]=$value;
                    }
                }
                unset($_GET[$query]);
                unset($_REQUEST[$query]);
            }
        }

            Log::debug("frame_run","路由重写开始");
            //不允许的参数
            if (isset($_REQUEST['m']) || isset($_REQUEST['a']) || isset($_REQUEST['c'])) {
                new RouteError("以下参数名不允许：m,a,c!");
            }
            $url = strtolower(urldecode($_SERVER['REQUEST_URI']));
            $data = null;
            $instance  = Cache::init(365 * 24 * 60 * 60, APP_ROUTE);
            if (!isDebug()) {//非调试状态从缓存读取
                //初始化路由缓存，不区分大小写
                $data =$instance->get($url);
            }
            if ($data !== null && isset($data['real']) && isset($data['route'])) {
                $route_arr_cp = $data['route'];
            } else {
                $route_arr = self::convertUrl();
                if (!isset($route_arr['m']) || !isset($route_arr['a']) || !isset($route_arr['c'])) {
                    if(StringUtil::get($url)->endsWith("favicon.ico")){
                        //不需要fav
                        exitApp("无图标");
                    }
                    Log::debug("frame_error","路由地址：$url");
                    new RouteError("错误的路由! 我们需要至少三个参数.");
                }
                $route_arr = array_merge($_GET, $route_arr);//get中的参数直接覆盖
                $route_arr_cp = $route_arr;
                //重写缓存表
                $__module = ($route_arr['m']);
                unset($route_arr['m']);

                $__controller = ($route_arr['c']);
                unset($route_arr['c']);

                $__action = ($route_arr['a']);
                unset($route_arr['a']);

                $nowUrl=urldecode(Response::getNowAddress());
                $defineUrl=urldecode(url($__module, $__controller, $__action, $route_arr));
                if (strtolower($defineUrl)!== strtolower($nowUrl)) {
                    new RouteError("错误的路由，该路由已被定义.\n当前地址:" . $nowUrl . '  定义的路由为:' . $defineUrl.",您应当通过【定义的路由】进行访问。");
                }

                $real = "$__module/$__controller/$__action";
                if (sizeof($route_arr)) {
                    $real .= '?' . http_build_query($route_arr);
                }
                $arr = [
                    'real' => $real,
                    'route' => $route_arr_cp,
                ];
                if (!isDebug())
                    $instance->set($url, $arr);

            }

        $_REQUEST = array_merge($_GET, $_POST, $route_arr_cp);

        global $__module, $__controller, $__action;
        $__module = $_REQUEST['m'];
        $__controller = $_REQUEST['c'];
        $__action = $_REQUEST['a'];
        Log::debug("frame_run","路由完成");
        self::isInstall();
    }

    /**
     * 路由匹配
     * @param string $url
     * @return array
     */
    public static function convertUrl(string $url=""): array
    {
        $isRewrite=$GLOBALS["frame"]["rewrite"];
        $route_arr = [];
        if($url==""){
            $url = strtolower( $_SERVER['HTTP_HOST'] .($isRewrite? $_SERVER['REQUEST_URI']:str_replace("//","/","/".$_SERVER["QUERY_STRING"])));
        }

        Log::debug("frame_run","正在匹配路由表：$url");


        if (strpos($url, '?') !== false) {
            $url = substr($url, 0, strpos($url, '?'));
        }

        foreach ($GLOBALS['route'] as $rule => $mapper) {
            Log::debug("frame_run","尝试匹配：$rule");
            $rule = Response::getDomain() . '/' . $rule;
            Log::debug("frame_run","尝试匹配2：$rule");
            $rule = strtolower($rule);
            $rule = '/' . str_ireplace(
                    ['\\\\',  '/', '<', '>', '.'],
                    ['',  '\/', '(?P<', '>[\x{4e00}-\x{9fa5}a-zA-Z0-9_\.-\/]+)', '\.'], $rule) . '$/u';
            Log::debug("frame_run","路由规则：".print_r($rule,true));
            if (preg_match($rule, $url, $matchs)) {
                $route = explode("/", $mapper);
                if (isset($route[2])) {
                    [$route_arr['m'], $route_arr['c'], $route_arr['a']] = $route;
                }
                foreach ($matchs as $matchkey => $matchval) {
                    if (!is_int($matchkey)) $route_arr[$matchkey] = $matchval;
                }
                Log::debug("frame_run","已匹配路由：".print_r($rule,true));
                break;
            }

        }
        Log::debug("frame_run","最终路由数据：".print_r($route_arr,true));
        Log::debug("frame_run","路由用时：".(microtime(true)-$GLOBALS['route_start'])."ms");

        return $route_arr;
    }
    /**
     *  判断是否有安装程序，有就跳转
     */
    private static function isInstall(){

        if($GLOBALS["frame"]["install"]!==""&&!is_file(APP_CACHE.'install.lock')){
            global $__controller;
            if(StringUtil::get($GLOBALS["frame"]["install"])->contains($__controller))return;
            //没有锁
            Response::location($GLOBALS["frame"]["install"]);
        }
    }

}





