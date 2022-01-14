<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\extend\ankioTask\core;

use app\core\mvc\Model;
use app\lib\Async\Async;

/**
 * Class Tasker
 * @package app\extend\net_ankio_tasker\core
 * Date: 2020/12/23 23:46
 * Author: ankio
 * Description: 定时任务管理器
 */

class Tasker extends Model
{
    private static Tasker $instance;

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
     * 获取对象实例
     * @return Tasker
     */
    public static function getInstance(): Tasker
    {
        return self::$instance===null?(self::$instance=new Tasker()):self::$instance;
    }

    public static function getTimes($id): int
    {
        $data=self::getInstance()->select("times")->table("extend_tasker")->where(["id"=>$id])->limit("1")->commit();
        if(!empty($data)){
            return 1 - intval($data[0]["times"]);
        }
        return 0;
    }
        /**
     * 清空所有定时任务
     * @return void
     */
    public static function clean(){
        self::getInstance()->emptyTable("extend_tasker");
    }

    /**
     * 删除指定ID的定时任务
     * @param $id
     * @return void
     */
    public static function del($id){
        self::getInstance()->delete()->table("extend_tasker")->where(["id"=>$id])->commit();
    }

    /**
     * 添加一个定时任务，与linux定时任务语法完全一致
     * @param array $package 定时任务时间包
     * @param string $url    执行的URL
     * @param int $times  执行次数,-1不限制
     * @return int 返回定时任务ID
     */
    public function add(array $package, string $url, $identify, int $times=-1){
        if(sizeof($package)!=5)return false;
        $minute=$package[0];$hour=$package[1];$day=$package[2];$month=$package[3];$week=$package[4];
        $time=$this->getNext($minute,$hour,$day,$month,$week);
        return self::getInstance()->insert(SQL_INSERT_NORMAL)->table("extend_tasker")->keyValue(
            ["minute"=>$minute,
                "hour"=>$hour,
                "day"=>$day,
                "month"=>$month,
                "week"=>$week,
                "url"=>$url,
                "times"=>$times,
                "identify"=>$identify,
                "next"=>$time
            ])->commit();
    }

    /**
     * 执行一次遍历数据库
     * @return void
     */
    public function run(){
        $db=self::getInstance();
        $data=$db->select()->table("extend_tasker")->commit();
        foreach ($data as $value){
            if(intval($value["times"])==0){
                $db->delete()->table("extend_tasker")->where(["id"=>$value["id"]])->commit();
            }elseif($value["next"]<=time()){
                $time=$this->getNext($value["minute"],$value["hour"],$value["day"],$value["month"],$value["week"]);
                $db->update()->table("extend_tasker")->where(["id"=>$value["id"]])->set(["times=times-1","next"=>$time])->commit();
                $this->startTasker($value["url"],$value["identify"]);
            }
        }
    }

    /**
     * 以天为周期
     * @param $hour int 小时
     * @param $minute int 分钟
     * @return array
     */
    public function cycleDay(int $hour, int $minute): array
    {
        return [$minute,$hour,1,0,0];
    }

    /**
     * 以N天为周期
     * @param $day int 天数
     * @param $hour int 时间
     * @param $minute int 分钟
     * @return array
     */
    public function cycleNDay(int $day, int $hour, int $minute): array
    {
        return [$minute,$hour,$day,0,0];
    }

    /**
     * 以N小时为周期
     * @param $hour int 小时
     * @param $minute int 分钟
     * @return array
     */
    public function cycleNHour(int $hour, int $minute): array
    {
        return [$minute,$hour,1,0,0];
    }

    /**
     * 以小时为周期
     * @param $minute int 分钟
     * @return array
     */
    public function cycleHour(int $minute): array
    {
        return [$minute,1,0,0,0];
    }

    /**
     * 以N分钟为周期
     * @param $minute int 分钟
     * @return array
     */
    public function cycleNMinute(int $minute): array
    {
        return [$minute,0,0,0,0];
    }

    /**
     * 以周为周期
     * @param $week int 周数
     * @param $hour int 小时
     * @param $minute int 分钟
     * @return array
     */
    public function cycleWeek(int $week, int $hour, int $minute): array
    {
        return [$minute,$hour,0,0,$week];
    }

    /**
     * 以月为周期
     * @param $day int 天
     * @param $hour int 小时
     * @param $minute int 分钟
     * @return array
     */
    public function cycleMonth(int $day, int $hour, int $minute): array
    {
        return [$minute,$hour,$day,1,0];
    }

    /**
     * 计算下一次执行时间
     * @param $minute int 分钟
     * @param $hour int 时
     * @param $day int 天
     * @param $month int 月
     * @param $week int 周
     * @return float 返回下次执行时间
     */
    protected function getNext(int $minute, int $hour, int $day, int $month, int $week){
        $time=$minute*60+$hour*60*60+$day*60*60*24+$month*60*60*24*30+$week*60*60*24*7;
        return time()+$time;
    }

    /**
     * 启动一个任务
     * @param $url string 任务url
     * @param $identify string 唯一标识
     * @return void
     */
    private function startTasker(string $url, string $identify)
    {
        Async::getInstance()->request($url,"GET",[],[],$identify);
    }
}