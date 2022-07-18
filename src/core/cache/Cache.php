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

namespace core\cache;

use core\debug\Log;
use core\utils\FileUtil;

/**
 * Class Cache
 * @package core\cache
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
     */
	public static function init(int $exp_time = 0, string $path = APP_CACHE): Cache
    {
        self::$cache_expire = $exp_time;
        self::$cache_path = $path;

        if(!is_dir($path)){
           FileUtil::mkDir($path);
        }
        return new Cache();
    }


    /**
     * 删除缓存
     * @param string $key
     */
	public  function del(string $key)
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
	private function fileName(string $key): string
    {
        return self::$cache_path . md5($key);
    }


	/**
		 * 设置缓存
		 * @param string $key
	 * @param array|string|int $data
		 * @return bool
		 */
	public  function set(string $key, $data): bool
    {
        $values = serialize($data);
        $filename = $this->fileName($key);
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
     * @return mixed
     */
	public  function get(string $key)
    {
        $filename = self::fileName($key);
        if (!file_exists($filename) || !is_readable($filename)) {
            return "";
        }
        if (self::$cache_expire==0||time() < (filemtime($filename) + self::$cache_expire)) {
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
    public  function empty(){
        FileUtil::empty(self::$cache_path);
    }

}
