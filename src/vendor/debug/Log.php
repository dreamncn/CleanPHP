<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\vendor\debug;

use app\vendor\config\Config;

/**
 * +----------------------------------------------------------
 * Class Log
 * +----------------------------------------------------------
 * @package app\vendor\debug
 * +----------------------------------------------------------
 * Date: 2020/11/20 12:16 上午
 * Author: ankio
 * +----------------------------------------------------------
 * Desciption:日志类
 * +----------------------------------------------------------
 */
class Log
{
private static $instance = null;
private static $validTime = 30;
private  $file = null;
		private $handler=[];//实例
		private $level;//日志有效期30天

	/**
	 * +----------------------------------------------------------
	 * 输出调试信息
	 * +----------------------------------------------------------
	 * @param $tag
	 * @param $msg
	 * +----------------------------------------------------------
	 */
	public static function debug($tag, $msg)
	{
		if ( ! isDebug()) {
			return;
		}
		$self = self::getInstance($tag, 1);
		$self->write(1, $msg);
	}

	/**
	 * +----------------------------------------------------------
	 * 写入日志文件
	 * +----------------------------------------------------------
	 * @param $level
	 * @param $msg
	 * +----------------------------------------------------------
	 */
	protected function write($level, $msg)
	{
		$msg = '['.date('Y-m-d H:i:s').']['.$this->getLevelStr($level).'] '.$msg
			."\n";
		flock($this->handler, LOCK_EX);
		fwrite($this->handler, $msg, strlen($msg));
		flock($this->handler, LOCK_UN);
		fclose($this->handler);
		//删除指定日期之前的日志
		self::rm(date('Y-m-d', strtotime("- ".self::$validTime." day")));
	}

	/**
	 * +----------------------------------------------------------
	 * 获取等级
	 * +----------------------------------------------------------
	 * @param $level
	 * +----------------------------------------------------------
	 * @return string
	 * +----------------------------------------------------------
	 */
	private function getLevelStr($level)
	{
		switch ($level) {
			case 2:
				return 'info';
			case 4:
				return 'warn';
			case 8:
				return 'error';
			default:
				return 'debug';
		}
	}

	/**
	 * +----------------------------------------------------------
	 * 删除日志
	 * +----------------------------------------------------------
	 * @param $date
	 * @param $logName
	 * +----------------------------------------------------------
	 */
	public static function rm($date = null, $logName = null)
	{
		if ($date == null && $logName == null) {
			if(is_dir(APP_LOG)){
				rmdir(APP_LOG);
				mkdir(APP_LOG);
			}

		} elseif ($date !== null && $logName == null) {
			if(is_dir(APP_LOG.$date)){
				rmdir(APP_LOG.$date);
			}

		} elseif ($date !== null && $logName !== null) {
			if(is_file(APP_LOG.$date.DS.$logName)){
				unlink(APP_LOG.$date.DS.$logName);
			}

		}
	}

	/**
	 * +----------------------------------------------------------
	 * 输出警告
	 * +----------------------------------------------------------
	 * @param $tag
	 * @param $msg
	 * +----------------------------------------------------------
	 */
	public static function warn($tag, $msg)
	{
		$self = self::getInstance($tag, 4);
		$self->write(4, $msg);
	}

	/**
	 * +----------------------------------------------------------
	 * 获取实例
	 * +----------------------------------------------------------
	 * @param $tag
	 * @param $level
	 * +----------------------------------------------------------
	 * @return Log
	 * +----------------------------------------------------------
	 */
	public static function getInstance($tag, $level)
	{
		if (self::$instance == null) {
			self::$instance  = new Log();
			self::$validTime = Config::getInstance("frame")
				->getOne("logValidTime");
		}

		return self::$instance->setLevel($tag, $level);
	}

	/**
	 * +----------------------------------------------------------
	 * 设置日志文件名等
	 * +----------------------------------------------------------
	 * @param  string  $file
	 * @param  int     $level
	 * +----------------------------------------------------------
	 * @return Log
	 * +----------------------------------------------------------
	 */
	public function setLevel($file = '', $level = 15)
	{
		$this->level   = $level;
		if($this->file==$file)
			return $this;
		$file     = APP_LOG.date('Y-m-d').DS.$file.'.log';

		$dir_name = dirname($file);
		//目录不存在就创建
		if ( ! file_exists($dir_name)) {
			$this->mkdirs($dir_name);
		}
		$this->handler = fopen($file, 'a');


		return $this;
	}

	/**
	 * +----------------------------------------------------------
	 * 连续创建文件夹
	 * +----------------------------------------------------------
	 * @param $dir
	 * +----------------------------------------------------------
	 */
	public function mkdirs($dir)
	{
		if (is_dir(dirname($dir))) {
			mkdir($dir);
		} else {
			$this->mkdirs(dirname($dir));
		}
	}

	/**
	 * +----------------------------------------------------------
	 * 输出错误
	 * +----------------------------------------------------------
	 * @param $tag
	 * @param $msg
	 * +----------------------------------------------------------
	 */
	public static function error($tag, $msg)
	{
		$self      = self::getInstance($tag, 8);
		$debugInfo = debug_backtrace();
		$stack     = "[";
		foreach ($debugInfo as $key => $val) {
			if (array_key_exists("file", $val)) {
				$stack .= ",file:".$val["file"];
			}
			if (array_key_exists("line", $val)) {
				$stack .= ",line:".$val["line"];
			}
			if (array_key_exists("function", $val)) {
				$stack .= ",function:".$val["function"];
			}
		}
		$stack .= "]";
		$self->write(8, $stack.$msg);
	}

	/**
	 * +----------------------------------------------------------
	 * 输出信息
	 * +----------------------------------------------------------
	 * @param $tag
	 * @param $msg
	 * +----------------------------------------------------------
	 */
	public static function info($tag, $msg)
	{
		$self = self::getInstance($tag, 15);
		$self->write(2, $msg);
	}


}
