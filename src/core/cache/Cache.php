<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

/**
 * Cache.php
 * Created By Dreamn.
 * Date : 2020/5/17
 * Time : 6:47 下午
 * Description :  缓存类
 */

namespace app\core\cache;

/**
 * +----------------------------------------------------------
 * Class Cache
 * +----------------------------------------------------------
 * @package app\core\cache
 * +----------------------------------------------------------
 * Date: 2020/11/21 11:33 下午
 * Author: ankio
 * +----------------------------------------------------------
 * Desciption: 缓存类
 * +----------------------------------------------------------
 */
class Cache
{
    private static $cache_path = APP_CACHE;
    private static $cache_expire = 3600;
	private static $cache_security = false;


	/**
	 * +----------------------------------------------------------
	 * 缓存设置
	 * +----------------------------------------------------------
	 * @param  int     $exp_time  缓存时间
	 * @param  string  $path 缓存路径
	 * @param  bool    $security 使用安全缓存
	 * +----------------------------------------------------------
	 */
	public static function init($exp_time = 3600, $path = APP_CACHE,$security=true)
    {
        self::$cache_expire = $exp_time;
        self::$cache_path = $path;
        self::$cache_security=$security;
    }


	/**
	 * +----------------------------------------------------------
	 * 删除缓存
	 * +----------------------------------------------------------
	 * @param $key
	 * +----------------------------------------------------------
	 */
	public static function del($key)
    {
        $filename = self::fileName($key);
        if (file_exists($filename))
            unlink($filename);
	    if (file_exists($filename."_md5"))
		    unlink($filename."_md5");
    }

	/**
	 * +----------------------------------------------------------
	 * 获取缓存文件名
	 * +----------------------------------------------------------
	 * @param $key
	 * +----------------------------------------------------------
	 * @return string
	 * +----------------------------------------------------------
	 */
	private static function fileName($key)
    {
        return self::$cache_path . md5($key);
    }


	/**
	 * +----------------------------------------------------------
	 * 设置缓存
	 * +----------------------------------------------------------
	 * @param $key
	 * @param $data
	 * +----------------------------------------------------------
	 * @return bool
	 * +----------------------------------------------------------
	 */
	public static function set($key, $data)
    {
        $values = serialize($data);
        $filename = self::fileName($key);
        $file = fopen($filename, 'w');
        if ($file) {//able to create the file
            flock($file, LOCK_EX);
            fwrite($file, $values);
            flock($file, LOCK_UN);
            fclose($file);

            if(self::$cache_security){
            	//写入校验,防止出现意外篡改
	            file_put_contents($filename."_md5", md5_file($filename));
            }

            return true;
        } else return false;
    }

	/**
	 * +----------------------------------------------------------
	 * 获取缓存值
	 * +----------------------------------------------------------
	 * @param $key
	 * +----------------------------------------------------------
	 * @return mixed|null
	 * +----------------------------------------------------------
	 */
	public static function get($key)
    {
        $filename = self::fileName($key);
        if (!file_exists($filename) || !is_readable($filename)) {
            return null;
        }
        if (time() < (filemtime($filename) + self::$cache_expire)) {
            $file = fopen($filename, "r");
            if ($file) {
	            if(self::$cache_security){
		            //校验,防止出现意外篡改
		            if(!is_file($filename."_md5"))return null;
		            if(md5_file($filename)!==file_get_contents($filename."_md5"))
			            return null;
	            }

                flock($file, LOCK_SH);
                $data = fread($file, filesize($filename));
                flock($file, LOCK_UN);
                fclose($file);
                return unserialize($data);
            } else return null;
        } else {
            self::del($key);
            return null;
        }
    }
}
