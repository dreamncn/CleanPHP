<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

use app\core\debug\Dump;
use app\core\debug\Log;
use app\core\mvc\Controller;
use app\core\web\Route;


/*数据库常量*/
define('SQL_INSERT_NORMAL', 0);
define('SQL_INSERT_IGNORE', 1);
define('SQL_INSERT_DUPLICATE', 2);


/**
 * +----------------------------------------------------------
 * 生成符合路由规则的URL
 * +----------------------------------------------------------
 * @param  string  $m      模块名
 * @param  string  $c      控制器名
 * @param  string  $a      方法
 * @param  array   $param  参数数组
 * +----------------------------------------------------------
 * @return mixed|string
 * +----------------------------------------------------------
 */
function url($m = 'index', $c = 'main', $a = 'index', $param = [])
{
	return Route::url(...func_get_args());
}


/**
 * +----------------------------------------------------------
 * 输出变量内容
 * +----------------------------------------------------------
 * @param  null   $var   预输出的变量名
 * @param  false  $exit  输出变量后是否退出进程
 * +----------------------------------------------------------
 */
function dump($var, $exit = false)
{
	if (isConsole()) {
		$line = debug_backtrace()[0]['file'].':'.debug_backtrace()[0]['line'];
		echo $line."\n";
		var_dump($var);
		if ($exit) {
			Log::debug('Clean', 'Dump函数执行退出。' );
			Log::debug('Clean', '退出框架，总耗时: ' . strval((microtime(true) - $GLOBALS['frame_start']) * 1000) . 'ms');
			exit;	}

		return;
	}
	$line = debug_backtrace()[0]['file'].':'.debug_backtrace()[0]['line'];
	echo <<<EOF
<style>pre {display: block;padding: 9.5px;margin: 0 0 10px;font-size: 13px;line-height: 1.42857143;color: #333;word-break: break-all;word-wrap: break-word;background-color:#f5f5f5;border: 1px solid #ccc;border-radius: 4px;}</style><div style="text-align: left">
<pre class="xdebug-var-dump" dir="ltr"><small>{$line}</small>\r\n
EOF;

	$dump = new Dump();
	$dump->dumpType($var);

	echo '</pre></div>';

	if ($exit) {
		Log::debug('Clean', 'Dump函数执行退出。' );
		Log::debug('Clean', '退出框架，总耗时: ' . strval((microtime(true) - $GLOBALS['frame_start']) * 1000) . 'ms');
		exit;
	}
}


/**
 * +----------------------------------------------------------
 * 获取前端传来的POST或GET参数
 * +----------------------------------------------------------
 * @param  null  $name     参数名
 * @param  null  $default  默认参数值
 * @param  bool  $trim     是否去除空白
 * @param string $type     类型(str,bool,float,double,int),当返回所有数据时该校验无效。
 * +----------------------------------------------------------
 * @return array|mixed|string|null
 * +----------------------------------------------------------
 */
function arg($name = null, $default = null, $trim = true,$type="str")
{
	if ($name) {
		if ( ! isset($_REQUEST[$name])) {
			return $default;
		}
		$arg = $_REQUEST[$name];
		if ($trim) {
			$arg = trim($arg);
		}
	} else {
		$arg = $_REQUEST;
	}


	if(!is_array($arg)){
        switch ($type){
            case "str":$arg=strval($arg);break;
            case "int":$arg=intval($arg);break;
            case "bool":$arg=boolval($arg);break;
            case "float":$arg=floatval($arg);break;
            case "double":$arg=doubleval($arg);break;
            default:break;
        }
    }

	return $arg;
}



/**
 * +----------------------------------------------------------
 * 是否为调试模式
 * +----------------------------------------------------------
 * @return bool
 * +----------------------------------------------------------
 */
function isDebug()
{
	return isset($GLOBALS["frame"]['debug']) && $GLOBALS["frame"]['debug'];
}


/**
 * +----------------------------------------------------------
 * 是否为命令行模式
 * +----------------------------------------------------------
 * @return bool
 * +----------------------------------------------------------
 */
function isConsole()
{
	return isset($_SERVER['CLEAN_CONSOLE']) && $_SERVER['CLEAN_CONSOLE'];
}

/**
 * +----------------------------------------------------------
 * 退出框架运行
 * +----------------------------------------------------------
 * @param $msg
 * @param null $tpl 退出模板文件名
 * @param string $path 模板文件路径
 * @param array $data 模板文件所需变量
 * +----------------------------------------------------------\
 */
function exitApp($msg,$tpl=null,$path='',$data=[])
{
    if($tpl!==null){
        $obj = new Controller();
        $obj->setArray($data);
        $obj->setAutoPathDir($path);
        Log::debug('Clean', '退出展示模板: ' . $path.DS . $tpl . '.tpl');
        if (file_exists($path.DS . $tpl . '.tpl'))
          echo  $obj->display($tpl);
    }
    Log::info("Clean",'程序调用退出: ' . $msg);
    Log::debug('Clean', '程序调用退出: ' . $msg);
    Log::debug('Clean', '退出框架，总耗时: ' . strval((microtime(true) - $GLOBALS['frame_start']) * 1000) . 'ms');
    exit();
}


/**
 * +----------------------------------------------------------
 *  获取随机字符串
 * +----------------------------------------------------------
 * @param  int   $length  字符串长度
 * @param  bool  $upper   是否包含大写字母
 * @param  bool  $lower   是否包含小写字母
 * @param  bool  $number  是否包含数字
 * +----------------------------------------------------------
 * @return string
 * +----------------------------------------------------------
 */
function getRandom($length = 8, $upper = true, $lower = true, $number = true)
{
	$charsList = [
		'abcdefghijklmnopqrstuvwxyz',
		'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
		'0123456789',
	];
	$chars     = "";
	if ($upper) {
		$chars .= $charsList[0];
	}
	if ($lower) {
		$chars .= $charsList[1];
	}
	if ($number) {
		$chars .= $charsList[2];
	}
	if ($chars === "") {
		$chars = $charsList[2];
	}
	$password = '';
	for ($i = 0; $i < $length; $i++) {
		$password .= $chars[mt_rand(0, strlen($chars) - 1)];
	}

	return $password;
}

/**
 * +----------------------------------------------------------
 * 检查编码并转换成UTF-8
 * +----------------------------------------------------------
 * @param $string
 * +----------------------------------------------------------
 * @return string
 * +----------------------------------------------------------
 */
function chkCode($string)
{
	$encode = mb_detect_encoding($string, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5'));
	return mb_convert_encoding($string, 'UTF-8', $encode);
}


