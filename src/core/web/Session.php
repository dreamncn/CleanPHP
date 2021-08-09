<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\core\web;


/**
 * +----------------------------------------------------------
 * Class Session
 * +----------------------------------------------------------
 * @package app\core\web
 * +----------------------------------------------------------
 * Date: 2020/11/29 12:24 上午
 * Author: ankio
 * +----------------------------------------------------------
 * Desciption:Session操作类
 * +----------------------------------------------------------
 */
class Session
{
	private static $instance = null;


    /**
     * +----------------------------------------------------------
     * 获取实例
     * +----------------------------------------------------------
     * @return Session
     * +----------------------------------------------------------
     */
	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			$class          = __CLASS__;
			self::$instance = new $class();
		}

		return self::$instance;
	}

    /**
     * +----------------------------------------------------------
     * 启动session
     * +----------------------------------------------------------
     * @return void
     * +----------------------------------------------------------
     */
	public function start()
	{
        if (session_status() !==PHP_SESSION_ACTIVE) {
            session_start();
        }
	}

    /**
     * +----------------------------------------------------------
     * 获取sessionId
     * +----------------------------------------------------------
     * @return string
     * +----------------------------------------------------------
     */
	public function Id(){
	    return session_id();
    }

	/**
	 * +----------------------------------------------------------
	 * 设置session
	 * +----------------------------------------------------------
	 * @param         $name
	 * @param         $value
	 * @param  int    $expire  过期时间,单位秒
	 * +----------------------------------------------------------
	 */
	public function set($name, $value, $expire = 0)
	{
		if (is_array($value) || is_object($value)) {
			$value = json_encode($value);
		}
		if ($expire != 0) {
			$expire = time() + $expire;

		}
		$_SESSION[$name]           = $value;
        $_SESSION[$name."_expire"] = $expire;
	}


	/**
	 * +----------------------------------------------------------
	 * 获取session
	 * +----------------------------------------------------------
	 * @param $name
	 * +----------------------------------------------------------
	 * @return array|mixed
	 * +----------------------------------------------------------
	 */
	public function get($name)
	{
		if ( ! isset($_SESSION[$name])) {
			return null;
		}
		$value = $_SESSION[$name];
		if ( ! isset($_SESSION[$name."_expire"])) {


			return $value;
		}
		$expire = $_SESSION[$name."_expire"];

		if ($expire == 0 || $expire > time()) {
			return $value;
		}
		return null;
	}


	/**
	 * +----------------------------------------------------------
	 * 删除session
	 * +----------------------------------------------------------
	 * @param         $name
	 */
	public function delete($name)
	{
		if (isset($_SESSION[$name])) {
			unset($_SESSION[$name]);
		}
		if (isset($_SESSION[$name."_expire"])) {
			unset($_SESSION[$name."_expire"]);
		}
	}


}