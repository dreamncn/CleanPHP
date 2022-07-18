<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace core\config;

use core\debug\Log;
use core\error\AppError;

/**
 * Class Config
 * @package core\config
 * Date: 2020/11/19 12:22 上午
 * Author: ankio
 * Description:配置管理器
 */
class Config
{
    private static ?Config $instance = null;//配置文件数据
    private $fileData;//配置文件名
    private $fileName;//配置路径
    private string $path = APP_CONF;//实例

    /**
     * 注册配置信息
     */
    static public function register()
    {

        $conf = self::getInstance("frame")->setLocation(APP_CONF)->getAll();
        $GLOBALS["frame"] = $conf;
        Log::debug("frame_run", "配置文件读取注册");
        if (!in_array("0.0.0.0", $conf['host']) && !in_array($_SERVER["SERVER_NAME"], $conf['host'])) {
            new AppError("您的域名绑定错误，当前域名为：{$_SERVER["SERVER_NAME"]} , 请在 /config/frame.yml 第2行添加该域名。", APP_CONF . "frame.yml", $conf['host'][0]);
        }
        $GLOBALS["route"] = self::getInstance("route")->setLocation(APP_CONF)->getAll();
    }

    /**
     * 获取配置文件数组
     * @return mixed
     */
    public function getAll()
    {
        return $this->fileData;
    }

    /**
     * 获取实例
     * @param string $file 存储的配置文件地址，请使用相对地址
     * @return static
     */
    public static function getInstance(string $file): Config
    {
        if (self::$instance == null) {
            self::$instance = new Config();
        }
        self::$instance->fileData = "";
        self::$instance->fileName = "$file.yml";

        return self::$instance->setLocation(APP_CONF)->getConfigFile();
    }

    /**
     *  获取配置文件
     * @return $this
     */
    private function getConfigFile(): Config
    {
        $file = $this->path . $this->fileName;
        if (file_exists($file)) {
            $this->fileData = Spyc::YAMLLoad($file);
        }

        return $this;
    }

    /**
     * 设置配置文件路径
     * @param string $path
     * @return $this
     */
    public function setLocation(string $path): Config
    {
        $this->path = $path;
        return $this->getConfigFile();
    }

    /**
     * 获取配置文件里面一项
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->fileData[$key] ?? "";
    }

    /**
     * 设置整个配置文件数组
     * @param array $data
     */
    public function setAll(array $data)
    {
        $this->fileData = $data;
        $file = $this->path . $this->fileName;
        file_put_contents($file, Spyc::YAMLDump($this->fileData));
    }

    /**
     * 设置单个配置
     * @param string $key 参数名称
     * @param  $val
     */
    public function set(string $key,  $val)
    {
        $this->fileData[$key] = $val;
        $file = $this->path . $this->fileName;
        file_put_contents($file, Spyc::YAMLDump($this->fileData));
    }
}
