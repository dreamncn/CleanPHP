<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/


use app\core\core\ArgType;
use app\core\debug\Dump;
use app\core\debug\Log;
use app\core\mvc\Controller;

use app\core\web\Route;



/**
 * 生成符合路由规则的URL
 * @param string $m      模块名
 * @param string $c      控制器名
 * @param string $a      方法
 * @param array $param  参数数组
 *
 * @return string
 */
function url(string $m = 'index', string $c = 'main', string $a = 'index', array $param = []): string
{
	return Route::url(...func_get_args());
}


function dumpAll(){
   $args =  func_get_args();
   foreach ($args as $arg){
       dump($arg);
   }
    exitApp("Dump函数执行退出.");
}
/**
 * 输出变量内容
 * @param  null   $var   预输出的变量名
 * @param false $exit  输出变量后是否退出进程
 */
function dump($var, bool $exit = false,bool $noTitle=false)
{
    if($noTitle){
        $line = "";
    }else{
        $line = debug_backtrace()[0]['file'].':'.debug_backtrace()[0]['line']."\n";
    }

    if (isConsole()) {
        echo $line;
		var_dump($var);
		if ($exit) {
            exitApp("Dump函数执行退出.");
        }

		return;
	}
    if(!$noTitle){
        echo <<<EOF
<style>pre {display: block;padding: 9.5px;margin: 0 0 10px;font-size: 13px;line-height: 1.42857143;color: #333;word-break: break-all;word-wrap: break-word;background-color:#f5f5f5;border: 1px solid #ccc;border-radius: 4px;}</style><div style="text-align: left">
<pre class="xdebug-var-dump" dir="ltr"><small>{$line}</small>\r\n
EOF;
    }else{
        echo <<<EOF
<style>pre {display: block;padding: 9.5px;margin: 0 0 10px;font-size: 13px;line-height: 1.42857143;color: #333;word-break: break-all;word-wrap: break-word;background-color:#f5f5f5;border: 1px solid #ccc;border-radius: 4px;}</style><div style="text-align: left"><pre class="xdebug-var-dump" dir="ltr">
EOF;
    }
	$dump = new Dump();
	echo $dump->dumpType($var);
	echo '</pre></div>';
	if ($exit) {
        exitApp("Dump函数执行退出.");
	}
}


/**
 * 获取前端传来的POST或GET参数
 * @param  ?string $name     参数名
 * @param  ?string $default  默认参数值
 * @param string $type     类型(使用{@link ArgType}构造),当返回所有数据时该校验无效。
 * @return mixed
 */
function arg(string $name = null, string $default = null, string $type= ArgType::STRING)
{
	if ($name) {
		if (!isset($_REQUEST[$name])) {
			return $default;
		}
		$arg = $_REQUEST[$name];
		if (is_string($arg)) {
			$arg = trim($arg);
		}
	} else {
		$arg = $_REQUEST;
	}


	if(!is_array($arg)){
        switch ($type){
            case  ArgType::STRING:$arg=strval($arg);break;
            case  ArgType::INT:$arg=intval($arg);break;
            case  ArgType::BOOLEAN:$arg=boolval($arg);break;
            case  ArgType::FLOAT:$arg=floatval($arg);break;
            case  ArgType::DOUBLE:$arg=doubleval($arg);break;
            default:break;
        }
    }

	return $arg;
}



/**
 * 是否为调试模式
 * @return bool
 */
function isDebug(): bool
{
	return isset($GLOBALS["frame"]['debug']) && $GLOBALS["frame"]['debug'];
}


/**
 * 是否为命令行模式
 * @return bool
 */
function isConsole(): bool
{
	return isset($_SERVER['CLEAN_CONSOLE']) && $_SERVER['CLEAN_CONSOLE'];
}

/**
 * 是否为mvc模式
 * @return bool
 */
function isMVC(): bool
{
   return $GLOBALS["frame"]["mode"]=="mvc";
}

/**
 * 是否为SPA模式
 * @return bool
 */
function isSPA(): bool
{
    return $GLOBALS["frame"]["mode"]=="spa";
}

/**
 * 退出框架运行
 * @param string $msg
 * @param string|null $tpl 退出模板文件名
 * @param string $path 模板文件路径
 * @param array $data 模板文件所需变量
 */
function exitApp(string $msg, string $tpl=null, string $path='', array $data=[])
{
    if($tpl!==null&&isMVC()){
        $obj = new Controller();
        $obj->setArray($data);
        $obj->setAutoPathDir($path);
       if (file_exists($path.DS . $tpl . '.tpl'))
          echo  $obj->display($tpl);
        else
          echo "";
    }
    Log::debug("frame_run","框架退出消息:".$msg);
    Log::debug("frame_run","框架响应时长:".(microtime(true) - $GLOBALS['frame_start']) . "ms");
    Log::debug("frame_run","------------> 框架结束 <------------");

    exit();
}


