<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace core\mvc;

use core\debug\Log;
use core\event\EventManager;
use core\utils\FileUtil;
use core\utils\StringUtil;

/**
 * Class Controller
 * @package core\mvc
 * Date: 2020/12/3 10:52 下午
 * Author: ankio
 * Description:控制器
 */
class Controller
{

    public $_auto_display = true;//layout布局文件

    protected $_v;//是否自动展示

    private $layout = '';//非自动定位的view的路径的真实路径

    private $_auto_path_dir = '';//view对象

    private $_data = [];//模板参数数组

    private $encode = true;

    private $init_result = null;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        if(isDebug()&&isMVC()){
            //调试模式下复制public
            $public = APP_DIR.DS."static".DS."public".DS;
            if(!is_dir($public))return;
            foreach (scandir($public) as $item){
                $path = $public.$item;
                if(StringUtil::get($item)->startsWith("."))
                    continue;
                elseif (is_dir($path)){
                    FileUtil::copy($path,APP_DIR.DS."public".DS.$item);
                }
            }

        }


        $this->init_result = $this->init();
    }
    public function __destruct()
    {

    }

    public function getInit()
    {
        return $this->init_result;
    }

    /**
     * 控制器初始化方法
     *
     */
    public function init()
    {
        global $__module, $__controller, $__action;
        return EventManager::fire("onControllerInit",[ "m"=>$__module,"c"=>$__controller,"a"=>$__action]);
    }

    /**
     * 设置layout文件
     * @param string $file
     */
    public function setLayout(string $file)
    {
        $this->layout = $file;
    }

    /**
     * 是否输出进行html编码
     * @param bool $encode
     */
    public function setEncode(bool $encode)
    {
        $this->encode = $encode;
    }

    /**
     * 是否输出进行html编码
     * @return bool
     */
    public function isEncode(): bool
    {
        return $this->encode === true;
    }

    /**
     * 设置自动编译目录
     * @param string $dir
     */
    public function setAutoPathDir(string $dir)
    {
        $this->_auto_path_dir = $dir;
    }

    /**
     * 设置模板数据
     * @param string $name
     * @param $value
     */
    function setData(string $name, $value)
    {
        $this->_data[$name] = $value;
    }

    /**
     * 设置模板数据数组
     * @param array $array
     */
    function setArray(array $array)
    {
        $this->_data = $array;
    }


    /**
     * 渲染模板
     * @param string $tpl_name
     * @return false|string
     */
    public function display(string $tpl_name)
    {
        $GLOBALS['display_start'] = microtime(true);
        if (!$this->_v) {
            $compile_dir = APP_TMP;
            if(!is_dir($compile_dir))mkdir($compile_dir,0777,true);
            if ($this->_auto_path_dir !== ""){
                if(!is_dir($this->_auto_path_dir))mkdir($this->_auto_path_dir,0777,true);
                $this->_v = new View($this->_auto_path_dir, $compile_dir);
            } else{
                if(!is_dir(APP_VIEW))mkdir(APP_VIEW,0777,true);
                $this->_v = new View(APP_VIEW, $compile_dir);
            }

        }
        $this->_v->assign(get_object_vars($this));
        $this->_v->assign($this->_data);
        if ($this->layout) {
            $this->_v->assign('__template_file', $tpl_name);
            $tpl_name = $this->layout;
        }
        $this->_auto_display = false;
        //   $this->encode = false;
        $result = $this->_v->render($tpl_name);
        if(isMVC()){
            $result = str_replace("../../public","",$result);
        }
        return $result;
    }
}
