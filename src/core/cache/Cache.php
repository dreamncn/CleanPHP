<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/

/**
 * Cache.php
 * Created By Dreamn.
 * Date : 2020/5/17
 * Time : 6:47 下午
 * Description :  缓存类
 */

namespace app\core\cache;

use app\core\debug\Debug;
use app\core\debug\Log;
use app\core\utils\FileUtil;

/**
 * Class Cache
 * @package app\core\cache
 * Date: 2020/11/21 11:33 下午
 * Author: ankio
 * Description: 缓存类
 */

class Cache
{
    private static string $cache_path = APP_CACHE;
    private static int $cache_expire = 3600;

    /**
     * @param int $exp_time 超时时间，单位为秒
     * @param string $path 缓存路径
     * @return void
     */
	public static function init(int $exp_time = 3600, string $path = APP_CACHE)
    {
        self::$cache_expire = $exp_time;
        self::$cache_path = $path;

        if(!is_dir($path)){
           FileUtil::mkDir($path);
        }
    }


    /**
     * 删除缓存
     * @param string $key
     */
	public static function del(string $key)
    {
        $filename = self::fileName($key);
        if (file_exists($filename))
            unlink($filename);
    }

    /**
     * 获取缓存文件名
     * @param string $key
     * @return string
     */
	private static function fileName(string $key): string
    {
        return self::$cache_path . md5($key);
    }


	/**
		 * 设置缓存
		 * @param string $key
	 * @param array|string|int $data
		 * @return bool
		 */
	public static function set(string $key, $data): bool
    {
        $values = serialize($data);
        $filename = self::fileName($key);
        $file = fopen($filename, 'w');
        if ($file) {//able to create the file
            flock($file, LOCK_EX);
            fwrite($file, $values);
            flock($file, LOCK_UN);
            fclose($file);
            return true;
        } else return false;
    }

    /**
     * 获取缓存值
     * @param string $key
     * @return mixed|string
     */
	public static function get(string $key)
    {
        $filename = self::fileName($key);
        if (!file_exists($filename) || !is_readable($filename)) {
            return "";
        }
        if (self::$cache_expire==-1||time() < (filemtime($filename) + self::$cache_expire)) {
            $file = fopen($filename, "r");
            if ($file) {
                flock($file, LOCK_SH);
                $data = fread($file, filesize($filename));
                flock($file, LOCK_UN);
                fclose($file);
               try{
                   return unserialize($data);
               }catch (\Throwable $e){
                   Log::info("frame_error","缓存读取失败".$e->getMessage());
                   return "";
               }
            } else return "";
        } else {
            self::del($key);
            return "";
        }
    }

    /**
     * 清空缓存
     */
    public static function clean(){
        FileUtil::cleanDir(self::$cache_path);
    }

}
