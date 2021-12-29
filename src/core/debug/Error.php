<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\core\debug;


use app\core\web\Response;

/**
 * +----------------------------------------------------------
 * Class Error
 * +----------------------------------------------------------
 * @package app\core\debug
 * +----------------------------------------------------------
 * Date: 2020/11/20 12:09 上午
 * Author: ankio
 * +----------------------------------------------------------
 * Desciption:框架错误处理
 * +----------------------------------------------------------
 */
class Error
{

	/**
	 * +----------------------------------------------------------
	 * 注册错误处理机制
	 * +----------------------------------------------------------
	 */
	public static function register()
	{
		error_reporting(E_ALL);
		set_error_handler([__CLASS__, 'appError']);
		set_exception_handler([__CLASS__, 'appException']);
		register_shutdown_function([__CLASS__, 'appShutdown']);
	}


	/**
	 * +----------------------------------------------------------
	 * 异常退出
	 * +----------------------------------------------------------
	 * @param $e
	 * +----------------------------------------------------------
	 */
	public static function appException($e)
	{
		$err = explode('Stack trace:', $e);
		if (sizeof($err) !== 2) {
			self::err($e);
		} else {
			$msg       = $err[0];
			$isMatched = preg_match_all('/in\s(.*php):([0-9]+)/', $msg,
				$matches);
			if ($isMatched) {
				$trace["file"] = $matches[1][0];
				$trace["line"] = $matches[2][0];
				$traces[]      = $trace;
			}
			$isMatched = preg_match_all('/#[0-9]+\s(.*php)\((.*?)\):/', $err[1],
				$matches);
			if ($isMatched) {
				for ($i = 0; $i < $isMatched; $i++) {
					$trace["file"] = $matches[1][$i];
					$trace["line"] = $matches[2][$i];
					$traces[]      = $trace;
				}
				self::err($msg, $traces);
			} else {
				self::err($msg);
			}
		}
	}


	/**
	 * +----------------------------------------------------------
	 * 报错退出
	 * +----------------------------------------------------------
	 * @param         $msg
	 * @param  array  $errinfo
	 * +----------------------------------------------------------
	 */
	public static function err($msg, $errinfo = [])
	{
		$msg   = htmlspecialchars($msg);
		$traces = sizeof($errinfo) === 0 ? debug_backtrace() : $errinfo;
		if (ob_get_contents()) {
			ob_end_clean();
		}

		Log::warn("error", $msg);


		if ( ! isDebug()) {
			global $__module, $__controller, $__action;
			$nameBase = "app\\controller\\$__module\\BaseController";

			if (method_exists($nameBase, 'err500')) {
				$nameBase::err500($__module, $__controller, $__action, $msg);
			} else {
				Response::msg(true, 500, 'System Error', 'Something bad.', 3,
					'/', '立即跳转');
			}
		} else {
			global $__module;
			$__module = '';
			self::display($msg, $traces);
		}
		Log::debug('Clean', '出现异常: ' . $msg);
		Log::debug('Clean', '退出框架，总耗时: ' . strval((microtime(true) - $GLOBALS['frame_start']) * 1000) . 'ms');
		exit();
	}


	/**
	 * +----------------------------------------------------------
	 * 渲染错误
	 * +----------------------------------------------------------
	 * @param $msg
	 * @param $traces
	 * +----------------------------------------------------------
	 */
	public static function display($msg, $traces)
	{
		if (isConsole()) {
			echo $msg."\n";

			foreach ($traces as $trace) {
				if (is_array($trace) && ! empty($trace["file"])) {
					echo "{$trace["file"]} on line {$trace["line"]}"."\n";
				}
			}

			return;
		}

		echo <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="">
<head>
    <meta name="robots" content="noindex, nofollow, noarchive"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{$msg}</title>
    <style type="">body {
            padding: 0;
            margin: 0;
            word-wrap: break-word;
            word-break: break-all;
            font-family: Courier, Arial, sans-serif;
            background: #EBF8FF;
            color: #5E5E5E;
        }

        div, h2, p, span {
            margin: 0;
            padding: 0;
        }

        ul {
            margin: 0;
            padding: 0;
            list-style-type: none;
            font-size: 0;
            line-height: 0;
        }

        #body {
            width: 918px;
            margin: 0 auto;
        }

        #main {
            width: 918px;
            margin: 13px auto 0 auto;
            padding: 0 0 35px 0;
        }

        #contents {
            width: 918px;
            float: left;
            margin: 13px auto 0 auto;
            background: #FFF;
            padding: 8px 0 0 9px;
        }

        #contents h2 {
            display: block;
            background: #CFF0F3;
            font: bold: 20px;
            padding: 12px 0 12px 30px;
            margin: 0 10px 22px 1px;
        }

        #contents ul {
            padding: 0 0 0 18px;
            font-size: 0;
            line-height: 0;
        }

        #contents ul li {
            display: block;
            padding: 0;
            color: #8F8F8F;
            background-color: inherit;
            font: normal 14px Arial, Helvetica, sans-serif;
            margin: 0;
        }

        #contents ul li span {
            display: block;
            color: #408BAA;
            background-color: inherit;
            font: bold 14px Arial, Helvetica, sans-serif;
            padding: 0 0 10px 0;
            margin: 0;
        }

        #oneborder {
            width: 800px;
            font: normal 14px Arial, Helvetica, sans-serif;
            border: #EBF3F5 solid 4px;
            margin: 0 30px 20px 30px;
            padding: 10px 20px;
            line-height: 23px;
        }

        #oneborder span {
            padding: 0;
            margin: 0;
        }

        #oneborder #current {
            background: #CFF0F3;
        }
        pre{
         
           white-space: pre-wrap;
        }
        </style>
</head>
<body>
<div id="main">
    <div id="contents"><h2><pre>{$msg}</pre></h2>
EOF;
        $index = 0;
		foreach ($traces as $trace) {
            if(++$index==1)continue;
			if (is_array($trace) && ! empty($trace["file"])) {
				$souceline = self::_err_getsource($trace["file"],
					$trace["line"]);
				if ($souceline) {
					echo <<<EOF
                <ul><li><span>{$trace["file"]} on line {$trace["line"]} </span></li></ul>
                <div id="oneborder">
EOF;
					foreach ($souceline as $singleline) {
						echo $singleline;
					}
					echo '</div>';
				}
			}
		}

		echo <<<EOF
        </div>
</div>
<div style="clear:both;padding-bottom:50px;"></div>
</body>
</html>
EOF;
	}


	/**
	 * +----------------------------------------------------------
	 * 获取高亮源码
	 * +----------------------------------------------------------
	 * @param $file
	 * @param $line
	 * +----------------------------------------------------------
	 * @return array|mixed
	 * +----------------------------------------------------------
	 */
	public static function _err_getsource($file, $line)
	{
		if ( ! (file_exists($file) && is_file($file))) {
			return '';
		}
		$data  = file($file);
		$count = count($data) - 1;
		$start = $line - 5;
		if ($start < 1) {
			$start = 1;
		}
		$end = $line + 5;
		if ($end > $count) {
			$end = $count + 1;
		}
		$returns = [];
		for ($i = $start; $i <= $end; $i++) {
			if ($i == $line) {
				$returns[] = "<div id='current'>".$i.".&nbsp;"
					.self::_err_highlight_code($data[$i - 1])."</div>";
			} else {
				$returns[] = $i.".&nbsp;".self::_err_highlight_code($data[$i
					- 1]);
			}
		}

		return $returns;
	}


	/**
	 * +----------------------------------------------------------
	 * 代码高亮
	 * +----------------------------------------------------------
	 * @param $code
	 * +----------------------------------------------------------
	 * @return string|string[]
	 * +----------------------------------------------------------
	 */
	public static function _err_highlight_code($code)
	{
		$code = preg_replace('/(\/\*\*)/', '///**', $code);
		$code = preg_replace('/(\s\*)[^\/]/', '//*', $code);
		$code = preg_replace('/(\*\/)/', '//*/', $code);
		if (preg_match('/<\?(php)?[^[:graph:]]/i', $code)) {
			$return = highlight_string($code, true);
		} else {
			$return = preg_replace('/(&lt;\?php&nbsp;)+/i', "",
				highlight_string("<?php ".$code, true));
		}

		return str_replace(['//*/', '///**', '//*'], ['*/', '/**', '*'],
			$return);
	}


	/**
	 * +----------------------------------------------------------
	 * 报错退出
	 * +----------------------------------------------------------
	 * @param          $errno
	 * @param          $errstr
	 * @param  string  $errfile
	 * @param  int     $errline
	 * +----------------------------------------------------------
	 */
	public static function appError(
		$errno,
		$errstr,
		$errfile = '',
		$errline = 0
	) {
		if (0 === error_reporting() || 30711 === error_reporting()) {
			return;
		}
		$msg = "ERROR";
		if ($errno == E_WARNING) {
			$msg = "WARNING";
		}
		if ($errno == E_NOTICE) {
			$msg = "NOTICE";
		}
		if ($errno == E_STRICT) {
			$msg = "STRICT";
		}
		if ($errno == 8192) {
			$msg = "DEPRECATED";
		}
		self::err("$msg: $errstr in $errfile on line $errline");
	}


	/**
	 * +----------------------------------------------------------
	 * 无法恢复的异常
	 * +----------------------------------------------------------
	 */
	public static function appShutdown()
	{
		if (error_get_last()) {
			$err = error_get_last();
			self::err("Fatal error: {$err['message']} in {$err['file']} on line {$err['line']}");
		}
	}


	/**
	 * +----------------------------------------------------------
	 * 错误路由
	 * +----------------------------------------------------------
	 * @param $msg
	 * +----------------------------------------------------------
	 */
	public static function _err_router($msg)
	{
		global $__module, $__controller, $__action;
		$nameBase = "app\\controller\\$__module\\BaseController";

		if ( ! isDebug()) {
			if (method_exists($nameBase, 'err404')) {
				$nameBase::err404($__module, $__controller, $__action, $msg);
			} else {
				Response::msg(true, 404, '404 Not Found', '无法找到该页面.', 3, '/','立即跳转');
			}
			Log::warn('route', $msg);
		} else {
			self::err($msg);
		}
		Log::debug('Clean', '出现路由错误: ' . $msg);
		Log::debug('Clean', '退出框架，总耗时: ' . strval((microtime(true) - $GLOBALS['frame_start']) * 1000) . 'ms');
		exit();
	}


}

