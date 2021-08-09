<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\core\config;

use Exception;

/**
 * +----------------------------------------------------------
 * Class Config
 * +----------------------------------------------------------
 * @package app\core\config
 * +----------------------------------------------------------
 * Date: 2020/11/19 12:22 上午
 * Author: ankio
 * +----------------------------------------------------------
 * Desciption:配置管理器
 * +----------------------------------------------------------
 */
class Config
{
	private static $instance = null;//配置文件数据
	private $fileData;//配置文件名
	private $fileName;//配置路径
	private $path = APP_CONF;//实例

	/**
	 * +----------------------------------------------------------
	 * 注册配置信息
	 * +----------------------------------------------------------
	 */
	static public function register()
	{
		$GLOBALS          = [];
		$conf             = self::getInstance("frame")->setLocation(APP_CONF)->get();
		$GLOBALS["frame"] = $conf;
		if ( ! in_array($_SERVER["HTTP_HOST"], $conf['host'])) {
			if (isDebug()) {
				exit("您的域名绑定错误，当前域名为：{$_SERVER["HTTP_HOST"]} , 请在 /config/frame.yml 第2行添加该域名。");
			}
			exit('网站出现错误，请联系网站管理员。');
		}
		$GLOBALS["route"] = self::getInstance("route")->setLocation(APP_CONF)->get();
	}

	/**
	 * +----------------------------------------------------------
	 * 获取配置文件数组
	 * +----------------------------------------------------------
	 * @return mixed
	 * +----------------------------------------------------------
	 */
	public function get()
	{
		return $this->fileData;
	}

	/**
	 * +----------------------------------------------------------
	 * 获取实例
	 * +----------------------------------------------------------
	 * @param $file
	 * +----------------------------------------------------------
	 * @return static
	 * +----------------------------------------------------------
	 */
	public static function getInstance($file)
	{
		if (self::$instance == null) {
			self::$instance = new Config();
		}
		self::$instance->fileData = "";
		self::$instance->fileName = "$file.yml";

		return self::$instance->getConfigFile();
	}

	/**
	 * +----------------------------------------------------------
	 *  获取配置文件
	 * +----------------------------------------------------------
	 * @return $this
	 * +----------------------------------------------------------
	 */
	private function getConfigFile()
	{
		$file = $this->path.$this->fileName;
		if (file_exists($file)) {
			$this->fileData = Spyc::YAMLLoad($file);
		}

		return $this;
	}

	/**
	 * +----------------------------------------------------------
	 * 设置配置文件路径
	 * +----------------------------------------------------------
	 * @param $path
	 * +----------------------------------------------------------
	 * @return $this
	 * +----------------------------------------------------------
	 */
	public function setLocation($path)
	{
		$this->path = $path;

		return $this->getConfigFile();
	}

	/**
	 * +----------------------------------------------------------
	 * 获取配置文件里面一项
	 * +----------------------------------------------------------
	 * @param $key
	 * +----------------------------------------------------------
	 * @return mixed|null
	 * +----------------------------------------------------------
	 */
	public function getOne($key)
	{
		return isset($this->fileData[$key]) ? $this->fileData[$key] : null;
	}

	/**
	 * +----------------------------------------------------------
	 * 设置整个配置文件数组
	 * +----------------------------------------------------------
	 * @param $data
	 * +----------------------------------------------------------
	 */
	public function setAll($data)
	{
		$this->fileData = $data;
		$file           = $this->path.$this->fileName;
		file_put_contents($file, Spyc::YAMLDump($this->fileData));
	}

	/**
	 * +----------------------------------------------------------
	 * 设置单个配置文件数组
	 * +----------------------------------------------------------
	 * @param $key
	 * @param $val
	 * +----------------------------------------------------------
	 */
	public function set($key, $val)
	{
		$this->fileData[$key] = $val;
		$file                 = $this->path.$this->fileName;
		file_put_contents($file, Spyc::YAMLDump($this->fileData));
	}
}
