<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\vendor\core;

use app\vendor\debug\Error;
use app\vendor\debug\Log;


/**
 * +----------------------------------------------------------
 * Class Loader
 * +----------------------------------------------------------
 * @package app\vendor\core
 * +----------------------------------------------------------
 * Date: 2020/11/19 11:47 下午
 * Author: ankio
 * +----------------------------------------------------------
 * Desciption:自动加载类
 * +----------------------------------------------------------
 */
class Loader
{
	/**
	 * +----------------------------------------------------------
	 * 注册自动加载
	 * +----------------------------------------------------------
	 */
	public static function register()
    {
        spl_autoload_register('app\\vendor\\core\\Loader::autoload', true, true);
        //注册第三方库的自动加载
        $data = scandir(APP_LIB);
        foreach ($data as $value) {
            if ($value != '.' && $value != '..') {
                $file = APP_LIB .  $value . DS . 'autoload.php';
                if (file_exists($file)) include $file;
            }
        }
    }
	/**
	 * +----------------------------------------------------------
	 * 框架本身的自动加载
	 * +----------------------------------------------------------
	 * @param string $realClass 自动加载的类名
	 * +----------------------------------------------------------
	 */
	public static function autoload($realClass)
    {
		//解析出路径与类名
        $classArr = self::getClass($realClass);
        $class = $classArr['class'] . '.php';
        $namespace = $classArr['namespace'];
		//拼接类名文件
        $file = APP_DIR . DS . str_replace('app/', '', $namespace) . DS . $class;
        //存在就加载
        if (file_exists($file)) {
            include_once $file;
	        Log::debug('loader', '加载 "' . $realClass . '"');
            return;
        }

	    Log::debug('loader', '默认加载器找不到指定类 "' . $realClass . '"(' . $file . ') , 它可能由第三方类的自动加载器提供。');

    }

	/**
	 * +----------------------------------------------------------
	 * 根据命名空间解析类名与路径
	 * +----------------------------------------------------------
	 * @param $class
	 * +----------------------------------------------------------
	 * @return array
	 * +----------------------------------------------------------
	 */
	public static function getClass($class)
    {
        if (strpos($class, '.')) Error::err('[Loader]"' . $class . '" 不是一个有效的类名！');
        $name = explode('\\', $class);
        $size = sizeof($name);
        $namespace = '';
        for ($i = 0; $i < $size - 1; $i++) {
            $namespace .= $name[$i] . (($i < $size - 2) ? '/' : '');
        }
        return ['namespace' => $namespace, 'class' => $name[$size - 1]];
    }

}
