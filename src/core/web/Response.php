<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

/**
 * File Response.php
 * Author : Dreamn
 * Date : 7/30/2020 12:49 AM
 * Description:响应类
 */

namespace app\core\web;

use app\core\debug\Log;
use app\core\mvc\Controller;

/**
 * Class Response
 * @package app\core\web
 * Date: 2020/11/22 11:21 下午
 * Author: ankio
 * Description:客户端响应类
 */
class Response
{

	/**
		 * 获取当前访问的URL域名
		 * @return string
		 */
	public static function getAddress()
    {
        return $GLOBALS['http_scheme'] . $_SERVER["HTTP_HOST"];
    }

    public static function getRootDomain(){
        $url="http://".$_SERVER ['HTTP_HOST'];
        $hosts = parse_url($url);
        $host = $hosts['host'];
        //查看是几级域名
        $data = explode('.', $host);
        $n = count($data);
        //判断是否是双后缀
        $preg = '/[\w].+\.(com|net|org|gov|edu)\.cn$/';
        if(($n > 2) && preg_match($preg,$host)){
            //双后缀取后3位
            $host = $data[$n-3].'.'.$data[$n-2].'.'.$data[$n-1];
        }else{
            //非双后缀取后两位
            $host = $data[$n-2].'.'.$data[$n-1];
        }
        return $host;
    }
    public static function getDomain(){
	    return $_SERVER["HTTP_HOST"];
    }
	/**
		 * 获取当前访问的地址
		 * @return string
		 */
	public static function getNowAddress()
    {
        return $GLOBALS['http_scheme'] . $_SERVER["HTTP_HOST"] . $_SERVER['REQUEST_URI'];
    }

	/**
		 * 获取当前服务器IP
		 */
    public static function getMyIp()
    {
        return gethostbyname(gethostname());
    }


	/**
		 * 跳转提示类
		 * @param  false   $err 是否错误
	 * @param  int     $code 错误代码（200、403、404等）
	 * @param  string  $title 错误标题
	 * @param  string  $msg 错误信息
	 * @param  int     $time 跳转时间
	 * @param  string  $url 跳转URL
	 * @param  string  $desc 跳转描述
		 */
	public static function msg($err = false, $code = 404, $title = "", $msg = "", $time = 3, $url = '', $desc = "立即跳转")
    {
        global $__module;
        $__module = '';
        header("Content-type: text/html; charset=utf-8", true, $code);
        $err = $err ? ":(" : ":)";

        if ($time == 0) {
            self::location($url);
            return;
        }
        $data = get_defined_vars();
        $obj = new Controller();
        $obj->setArray($data);
        $obj->setAutoPathDir(APP_INNER . DS . "tip");
        if (file_exists(APP_INNER . DS . "tip" . $code . '.tpl'))
           echo $obj->display($code);
        else {
            echo $obj->display('common');
        }
        exitApp("出现重定向或不可访问的页面。响应代码：  $code");
    }

    /**
     * 直接跳转
     * @param $url
     * @param int $timeout 延时跳转
     * @param bool $exit 发生跳转是否直接退出
     */
	public static function location($url,$timeout=0,$exit=true)
    {
        if($timeout!==0){
            header("refresh:$timeout,".$url);
        }else{
            header("Location:{$url}");
        }
        Log::debug('Clean', '发生强制跳转： '.$url );
        if($exit){
            exitApp("发生强制跳转：  $url");
        }

    }

    public static function isInner($ip){
        return preg_match('%^127\.|10\.|192\.168|172\.(1[6-9]|2|3[01])%',$ip);
    }
}
