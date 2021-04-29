<?php
/*******************************************************************************
 * Copyright (c) 2021. CleanPHP. All Rights Reserved.
 ******************************************************************************/

/**
 * +----------------------------------------------------------
 * File Record
 * +----------------------------------------------------------
 * @package app\extend\net_ankio_cc_defense
 * +----------------------------------------------------------
 * Date: 2020/12/4 12:04 上午
 * Author: ankio
 * +----------------------------------------------------------
 * Desciption: 本地数据库操作
 * +----------------------------------------------------------
 */

namespace app\extend\net_ankio_tasker\core;

use app\vendor\database\Sql;
use app\vendor\mvc\Model;

class Db extends Model
{
	private static $instance=null;


	public function __construct($tableName = '')
    {
        parent::__construct($tableName);
        $this->setDbLocation(EXTEND_TASKER."data".DS, "db");
        $this->setDatabase("sqlite");

    }

    /**
     * +----------------------------------------------------------
     * 获取对象实例
     * +----------------------------------------------------------
     * @return Db
     * +----------------------------------------------------------
     */
    public static function getInstance(){
        return self::$instance===null?(self::$instance=new Db()):self::$instance;
    }

    /**
     * +----------------------------------------------------------
     * 初始化异步服务存储库
     * +----------------------------------------------------------
     * @return void
     * +----------------------------------------------------------
     */
    public static function initAsync(){
        self::getInstance()->execute(
            "CREATE TABLE  IF NOT EXISTS extend_async(
                    id integer PRIMARY KEY autoincrement,
                    identify varchar(200),
                    timeout varchar(200),
                    token varchar(200)
                    )"
        );
    }

    /**
     * +----------------------------------------------------------
     *  初始化定时任务库
     * +----------------------------------------------------------
     * @return void
     * +----------------------------------------------------------
     */
    public static function initTasker(){
        self::getInstance()->execute(
            "CREATE TABLE  IF NOT EXISTS extend_tasker(
                    id integer PRIMARY KEY autoincrement,
                    url text,
                    identify varchar(200),
                    minute varchar(200),
                    hour varchar(200),
                    day varchar(200),
                    month varchar(200),
                    week varchar(200),
                    next varchar(200),
                    times integer
                    )"
        );
    }

    /**
     * +----------------------------------------------------------
     * 初始化Lock
     * +----------------------------------------------------------
     * @return void
     * +----------------------------------------------------------
     */
    public static function initLock(){
        self::getInstance()->execute(
            "CREATE TABLE  IF NOT EXISTS extend_lock(
                    lock_time varchar(200)
                    )"
        );
    }

}