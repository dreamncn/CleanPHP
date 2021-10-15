<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

/**
 * +----------------------------------------------------------
 * File BanIP
 * +----------------------------------------------------------
 * @package app\extend\net_ankio_cc_defense
 * +----------------------------------------------------------
 * Date: 2020/12/4 12:04 上午
 * Author: ankio
 * +----------------------------------------------------------
 * Desciption: 本地数据库操作
 * +----------------------------------------------------------
 */

namespace app\extend\net_ankio_cc_defense\core;

use app\core\database\Sql;
use app\core\mvc\Model;
use app\core\web\Request;

/**
 * +----------------------------------------------------------
 * Class BanIP
 * +----------------------------------------------------------
 * @package app\extend\net_ankio_cc_defense
 * +----------------------------------------------------------
 * Date: 2020/12/18 23:06
 * Author: ankio
 * +----------------------------------------------------------
 * Desciption: IP封禁
 * +----------------------------------------------------------
 */
class BanIP extends Model
{
	private static $instance=null;
	

    /**
     * BanIP constructor.
     */
	public function __construct() {
	    parent::__construct("ban_ip");
		
		$this->setDbLocation(EXTEND_CC_DEFENSE."data".DS, "db");
        $this->setDatabase("sqlite");
        $this->execute(
            "CREATE TABLE  IF NOT EXISTS ban_ip(
                    id integer PRIMARY KEY autoincrement,
                    ip varchar(200),
                    expire varchar(200)
                    )"
        );

	}

    /**
     * +----------------------------------------------------------
     * 获取实例
     * +----------------------------------------------------------
     * @return BanIP|null
     * +----------------------------------------------------------
     */
	public static function getInstance(){
		return self::$instance==null?self::$instance=new BanIP():self::$instance;
	}

    /**
     * +----------------------------------------------------------
     * 添加一个封禁IP
     * +----------------------------------------------------------
     * @param $time
     * +----------------------------------------------------------
     * @return void
     * +----------------------------------------------------------
     */
	public function add($time){
        $this->insert(SQL_INSERT_NORMAL)->keyValue(["ip"=>Request::getClientIP(),"expire"=>time()+$time])->commit();

	}

    /**
     * +----------------------------------------------------------
     * 获取当前IP的封禁信息
     * +----------------------------------------------------------
     * @return mixed|null
     * +----------------------------------------------------------
     */
	public function get(){
        $this->clear();
        $data=$this->select("*")
            ->where(["ip"=>Request::getClientIP()])
            ->limit("1")
            ->commit();
        if(!empty($data))return $data[0];
        return null;
    }

    /**
     * +----------------------------------------------------------
     * 清理解封IP
     * +----------------------------------------------------------
     * @return void
     * +----------------------------------------------------------
     */
	public function clear(){
        $this->delete()->where(["expire < :time",":time"=>time()])->commit();
	}
}