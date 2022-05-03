<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\core\mvc;



use app\core\database\Sql;

/**
 * Class Model
 * @package app\core\mvc
 * Date: 2020/12/3 12:21 上午
 * Author: ankio
 * Description: 模型类
 */
class Model extends Sql
{

    protected static  $instance = null;
    public static string $table = "";
    public static function getInstance()
    {
        if(self::$instance==null) {
            //
            self::$instance  = new static();
        }
        $classFullName = get_called_class();
        self::$instance->setTable($classFullName::$table);
        return self::$instance;

    }

    /**
     * Model constructor.
     *
     * @param string $table_name
     */
    public function setTable(string $table_name = '')
    {

        //手动设置默认数据库位置
        $this->setDbLocation(APP_CONF,"db")->setDatabase("master")->table($table_name);

       // print_r("table:$table_name\n");

    }


    /**
		 * 设置选项
		 * @param $idName
	 * @param $id
	 * @param $opt
	 * @param $val
		 * @return mixed
		 */
	public function setOption($idName, $id, $opt, $val)
    {
        return $this->update()->where([$idName => $id])->set([$opt => $val])->commit();
    }


}
