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

namespace app\extend\net_ankio_tasker\core\Db;

use app\core\mvc\Model;

class Tasker extends Model
{
	private static $instance=null;


	public function __construct()
    {
        parent::__construct("extend_tasker");
        $this->setDbLocation(EXTEND_TASKER."data".DS, "db");
        $this->setDatabase("sqlite");
        $this->execute(
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
     * 获取对象实例
     * +----------------------------------------------------------
     * @return Model
     * +----------------------------------------------------------
     */
    public static function getInstance(){
        return self::$instance===null?(self::$instance=new Tasker()):self::$instance;
    }

    public  function getTimes($id){
        $data=$this->select("times")->table("extend_tasker")->where(["id"=>$id])->limit("1")->commit();
        if(!empty($data)){
            return 1 - intval($data[0]["times"]);
        }
        return 0;
    }





}