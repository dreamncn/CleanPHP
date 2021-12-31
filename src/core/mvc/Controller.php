<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\core\mvc;


use app\core\debug\Log;

/**
 * Class Controller
 * @package app\core\mvc
 * Date: 2020/12/3 10:52 下午
 * Author: ankio
 * Desciption:控制器
 */
class Controller
{

    public $_auto_display = true;//layout布局文件

    protected $_v;//是否自动展示

    private $layout = '';//非自动定位的view的路径的真实路径

    private $_auto_path_dir = '';//view对象

    private $_data = [];//模板参数数组

    private $encode=true;

    private $init_result=null;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->init_result = $this->init();
    }

    public function getInit(){
        return $this->init_result;
    }

    /**
     * 控制器初始化方法
     * @return null|void|string|array
     */
    public function init()
    {
    }

    /**
     * 设置layout文件
     * @param $file
     */
    public function setLayout($file)
    {
        $this->layout = $file;
    }

    public function setEncode($encode){
        $this->encode=$encode;
    }

    public function isEncode(){
        return $this->encode===true;
    }

    /**
     * 设置自动编译目录
     * @param $dir
     */
    public function setAutoPathDir($dir)
    {
        $this->_auto_path_dir = $dir;
    }

    /**
     * 设置模板数据
     * @param $name
     * @param $value
     */
    function setData($name, $value)
    {
        $this->_data[$name] = $value;
    }

    /**
     * 设置模板数据数组
     * @param $array
     */
    function setArray($array)
    {
        $this->_data = $array;
    }


    /**
     * 渲染模板
     * @param         $tpl_name
     * @return false|string
     */
    public function display($tpl_name)
    {
        $GLOBALS['display_start'] = microtime(true);
        Log::debug('view', '尝试编译模板文件 "' . $tpl_name . '"');
        if (!$this->_v) {
            $compile_dir = APP_TMP;
            if ($this->_auto_path_dir !== "")
                $this->_v = new View($this->_auto_path_dir, $compile_dir);
            else
                $this->_v = new View(APP_VIEW, $compile_dir);
        }
        $this->_v->assign(get_object_vars($this));
        $this->_v->assign($this->_data);
        if ($this->layout) {
            $this->_v->assign('__template_file', $tpl_name);
            $tpl_name = $this->layout;
        }
        $this->_auto_display = false;
        $this->encode=false;
        return $this->_v->render($tpl_name);
    }
}
