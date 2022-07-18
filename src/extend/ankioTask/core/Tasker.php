<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\extend\ankioTask\core;

use app\core\cache\Cache;
use app\core\debug\Log;
use app\core\extend\Async\Async;
use app\core\web\Response;


/**
 * Class Tasker
 * @package app\extend\net_ankio_tasker\core
 * Date: 2020/12/23 23:46
 * Author: ankio
 * Description: 定时任务管理器
 */
class Tasker extends Db
{

    public static string $table = "extend_tasker";


    public function setDb()
    {
        parent::setDb();

        $this->execute(
            "CREATE TABLE IF NOT EXISTS extend_tasker(
                    id integer PRIMARY KEY AUTOINCREMENT ,
                    url text,
                    identify TEXT,
                    minute TEXT,
                    hour TEXT,
                    day TEXT,
                    month TEXT,
                    week TEXT,
                    next TEXT,
                    times integer,loop integer
                    )"
        );
    }


    public  function getTimes($id): int
    {
        $data=self::getInstance()->select("times")->table("extend_tasker")->where(["id"=>$id])->limit(1)->commit();
        if(!empty($data)){
            return 1 - intval($data[0]["times"]);
        }
        return 0;
    }

    /**
     * 清空所有定时任务
     * @return void
     */
    public  function clean(){
        self::getInstance()->emptyTable("extend_tasker");
    }

    /**
     * 删除指定ID的定时任务
     * @param $id
     * @return void
     */
    public  function del($id)
    {
        self::getInstance()->delete()->table("extend_tasker")->where(["id" => $id])->commit();
        $data = Cache::init(0, APP_CACHE . DS . "task" . DS)->get($id);
        if ($data == "") return;
        Cache::init(0, APP_CACHE . DS . "task" . DS)->set($id, "false");
    }

    /**
     * 获取指定id的定时任务
     * @param $id
     * @return mixed|null
     */
    public  function get($id){
        $data=self::getInstance()->select("*")->table("extend_tasker")->where(["id"=>$id])->limit(1)->commit();
        if(!empty($data)){
            return $data[0];
        }
        return null;
    }

    /**
     * 获取list
     * @return array|int
     */
    public function list(){
        return self::getInstance()->select("*")->table("extend_tasker")->commit();
    }

    /**
     * 添加一个定时任务，与linux定时任务语法完全一致
     * @param array $package 定时任务时间包
     * @param string $url 执行的URL
     * @param $identify
     * @param int $times
     * @param bool $loop
     * 返回定时任务ID
     * @return false|string
     */
    public function add(array $package, string $url, $identify, int $times=-1,bool $loop=false){
        if(sizeof($package)!=5)return false;
        $minute=$package[0];$hour=$package[1];$day=$package[2];$month=$package[3];$week=$package[4];
        $time=$this->getNext($minute,$hour,$day,$month,$week,$loop?1:0);

        Log::debug("tasker","添加定时任务：$identify");
        Log::debug("tasker","下次执行时间为：".date("Y-m-d H:i:s",$time));

        return self::getInstance()->insert()->table("extend_tasker")->keyValue(
            ["minute"=>$minute,
                "hour"=>$hour,
                "day"=>$day,
                "month"=>$month,
                "week"=>$week,
                "url"=>$url,
                "times"=>$times,
                "identify"=>$identify,
                "next"=>$time,
                "loop"=>$loop?1:0
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
                Log::info("tasker","该ID {$value["id"]} 的数据执行完毕，删掉");
                $db->delete()->table("extend_tasker")->where(["id"=>$value["id"]])->commit();
            }elseif($value["next"]<=time()) {
                $time = $this->getNext($value["minute"], $value["hour"], $value["day"], $value["month"], $value["week"], intval($value["loop"]));
                $db->update()->table("extend_tasker")->where(["id" => $value["id"]])->set(["times=times-1", "next" => $time])->commit();
                Log::info("tasker", "下次执行时间为：" . date("Y-m-d H:i:s", $time));

                $this->startTasker($value["url"], $value["identify"], $value["id"]);
            }
        }

    }


    /**
     * 计算下一次执行时间
     * @param $minute int 分钟
     * @param $hour int 时
     * @param $day int 天
     * @param $month int 月,这个月是不精确的，按照1个月30天来计算
     * @param $week int 周
     * @return float 返回下次执行时间
     */
    protected function getNext(int $minute, int $hour, int $day, int $month, int $week,int $loop){
        $time=$minute*60+$hour*60*60+$day*60*60*24+$month*60*60*24*30+$week*60*60*24*7;
        //if($day!=0||$month!=0||)
        if($loop==0){//如果是循环的话，每小时，每天，每周，每月
            $loopType = "Month";//循环类型
            $date = mktime(0,0,0,date('m'),1,date('Y'));//取当前月的第一天
            $add= $month*60*60*24*30;
            if($month==0){
                $loopType="Week";
                $date = mktime(0,0,0,date("m"),date("d")-date("w")+1,date("Y"));//取当前周的第一天
                $add= $week*60*60*24*7;
                if($week==0){
                    $loopType="Day";
                    $date = mktime(0,0,0,date('m'),date('d'),date('Y'));//获取当天的
                    $add= $day*60*60*24;
                    if($day==0){
                        $loopType="Hour";
                        $date = mktime($hour,0,0,date('m'),date('d'),date('Y'));//获取当天的
                        $add= $hour*60*60;
                    }
                }
            }
            //判断出循环类型
            $retTime = $date+$time;
            if ($add <= 0) $add = 60;
            while($retTime<time()){
                $retTime = $retTime+$add;
            }
        }else{
            $retTime = time()+$time;
        }
        return $retTime;
    }

    /**
     * 启动一个任务
     * @param $url string 任务url
     * @param $identify string 唯一标识
     * @return void
     */
    private function startTasker(string $url, string $identify, string $id)
    {
        Async::getInstance()->request(Response::getAddress() . "/tasker_server/task_request", "POST", ["task_id" => $id, "task_url" => $url, "task_name" => $identify], [], $identify);
    }
}