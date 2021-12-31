<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

/**
 * File Record
 * @package app\extend\net_ankio_cc_defense
 * Date: 2020/12/4 12:04 上午
 * Author: ankio
 * Desciption: 本地数据库操作
 */

namespace app\extend\net_ankio_cc_defense\core;

use app\core\database\Sql;
use app\core\mvc\Model;
use app\core\web\Response;

class Record extends Model
{
	private static $instance=null;


	public function __construct() {
        parent::__construct("session_record");

		$this->setDbLocation(EXTEND_CC_DEFENSE."data".DS, "db");
		$this->setDatabase("sqlite");
        $this->execute(
            "CREATE TABLE  IF NOT EXISTS session_record(
                    id integer PRIMARY KEY autoincrement,
                    session varchar(200),
                    count integer,
                    times integer,
                    check_in integer,
                    url text,
                    last_time varchar(200)
                    )"
        );
	}

    /**
     * 获取对象实例
     * @return Record
     */
	public static function getInstance(){
		return self::$instance===null?(self::$instance=new Record()):self::$instance;
	}

    /**
     * 添加一条记录
     * *
     * @param $id
     * @param $count
     * @param $times
     * @param $last_time
     * @return void
     */
	public function add($id,$count,$times,$last_time){

        $this->insert(SQL_INSERT_NORMAL)
            ->keyValue(
                ["session"=>$id,"count"=>$count,"times"=>$times,"last_time"=>$last_time,"url"=>Response::getNowAddress()]
            )->commit();

		$this->clear();
	}

    /**
     * 更新修改记录
     * * @param $id
     * @param $data
     * @return void
     */
	public function udp($id,$data){
        $this->update()->where(["session"=>$id])->set($data)->commit();
	}

    /**
     * 获取记录
     * * @param $session
     * @return array
     */
    public function get($session){
        $data=$this->select("*")
            ->where(["session"=>$session])
            ->limit("1")
            ->commit();
        if(!empty($data))return $data[0];
        return null;
    }
    /**
     * 清理缓存
     * @return void
     */
    public function clear(){
        $this->delete()->where(["last_time > :time",":time"=>time()+60*60*24])->commit();
    }


}