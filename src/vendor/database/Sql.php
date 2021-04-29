<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\vendor\database;


use app\vendor\database\sql\Delete;
use app\vendor\database\sql\Insert;
use app\vendor\database\sql\Page;
use app\vendor\database\sql\Select;
use app\vendor\database\sql\sqlExec;
use app\vendor\database\sql\Update;

/**
 * +----------------------------------------------------------
 * Class Sql
 * +----------------------------------------------------------
 * @package app\vendor\database
 * +----------------------------------------------------------
 * Date: 2020/11/22 11:05 下午
 * Author: ankio
 * +----------------------------------------------------------
 * Desciption:sql的集合类
 * +----------------------------------------------------------
 */
class Sql
{
    /**
     * @var string
     */
    protected $sqlIndex = "master";
    /**
     * @var mixed|string
     */
    private $tableName;
    /**
     * @var array
     */
    private $instances = [];


    /**
     * Sql constructor.
     * @param  string  $tableName
     */
    public function __construct($tableName = '')
    {
        $this->sql = new sqlExec();
        if($tableName!=="")
            $this->tableName = $tableName;
    }
    protected function table($tableName){
        $this->tableName = $tableName;
        return $this;
    }

    /**
     * +----------------------------------------------------------
     * select
     * +----------------------------------------------------------
     * @param  string  $field
     * +----------------------------------------------------------
     * @return Select
     * +----------------------------------------------------------
     */
    protected function select($field = "*")
    {
        return $this->sqlInstance("Select")->select($field);
    }


    /**
     * +----------------------------------------------------------
     * 获取sql实例
     * +----------------------------------------------------------
     * @param  string  $name
     * +----------------------------------------------------------
     * @return sqlExec|Insert|Select|Delete|Update
     * +----------------------------------------------------------
     */
    private function sqlInstance($name = "")
    {
        if ($name === "") return $this->sql;//为空直接获取执行实例
        $class = 'app\vendor\database\sql\\' . $name;
        if (isset($this->instances[$name]) && get_class($this->instances[$name]) === $class)
            return $this->instances[$name];

        if (class_exists($class)) {
            $this->instances[$name] = new $class($this->tableName, $this->sql);
        } else {
            return $this->sql;
        }
        return $this->instances[$name];
    }

    /**
     * +----------------------------------------------------------
     * 获取分页数据
     * +----------------------------------------------------------
     * @return Page
     * +----------------------------------------------------------
     */
    public function getPage()
    {
        return new Page($this->sqlInstance("Select")->getPage());
    }

    /**
     * +----------------------------------------------------------
     * 删除
     * +----------------------------------------------------------
     * @return Delete
     * +----------------------------------------------------------
     */
    protected function delete()
    {
        return $this->sqlInstance("Delete")->delete();
    }

    /**
     * +----------------------------------------------------------
     * 插入
     * +----------------------------------------------------------
     * @param $model int 数据库插入模式
     * +----------------------------------------------------------
     * @return Insert
     * +----------------------------------------------------------
     */
    protected function insert($model)
    {
        return $this->sqlInstance("Insert")->insert($model);
    }

    /**
     * +----------------------------------------------------------
     * 更新
     * +----------------------------------------------------------
     * @return Update
     * +----------------------------------------------------------
     */
    protected function update()
    {
        return $this->sqlInstance("Update")->update();
    }

    /**
     * +----------------------------------------------------------
     * 数据库执行
     * +----------------------------------------------------------
     * @param  string  $sql 执行的sql语句
     * @param  array  $params 绑定参数
     * @param  false  $readonly 是否只读
     * +----------------------------------------------------------
     * @return array|false|int
     * +----------------------------------------------------------
     */
    protected function execute($sql, $params = [], $readonly = false)
    {
        return $this->sqlInstance()->execute($sql, $params, $readonly);
    }

    /**
     * +----------------------------------------------------------
     * 输出所有查询的语句
     * +----------------------------------------------------------
     * @return array
     * +----------------------------------------------------------
     */
    public function dumpSql()
    {
        return $this->sql->dumpSql();
    }

    /**
     * +----------------------------------------------------------
     * 事务开始
     * +----------------------------------------------------------
     */
    protected function beginTransaction()
    {
        $this->sqlInstance()->execute("BEGIN");
    }

    /**
     * +----------------------------------------------------------
     * 事务回滚
     * +----------------------------------------------------------
     */
    protected function rollBack()
    {
        $this->sqlInstance()->execute("ROLLBACK");
    }

    /**
     * +----------------------------------------------------------
     * 事务提交
     * +----------------------------------------------------------
     */
    protected function commit()
    {
        $this->sqlInstance()->execute("COMMIT");
    }

    /**
     * +----------------------------------------------------------
     * 设置数据库配置文件位置
     * +----------------------------------------------------------
     * @param $path string 文件位置
     * @param $name string 文件名
     * +----------------------------------------------------------
     * @return $this
     * +----------------------------------------------------------
     */
    protected function setDbLocation($path, $name)
    {
        $this->sql->setDbFile($path, $name);
        return $this;
    }

    /**
     * +----------------------------------------------------------
     * 设置数据库配置文件中的配置选择
     * +----------------------------------------------------------
     * @param $dbName string 配置文件名
     * +----------------------------------------------------------
     * @return $this
     * +----------------------------------------------------------
     */
    protected function setDatabase($dbName)
    {
        $this->sql->setDatabase($dbName);
        $this->sqlIndex = $dbName;
        return $this;
    }

    /**
     * +----------------------------------------------------------
     * 清空数据表
     * +----------------------------------------------------------
     * @param $table_name string 预清空的数据表
     * +----------------------------------------------------------
     */
    protected function emptyTable($table_name)
    {
        $this->sqlInstance()->emptyTable($table_name);
    }


}
