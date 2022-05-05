<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\extend\ankioTask\core;


use app\core\database\sql\Insert;
use app\core\debug\Log;
use app\core\extend\Async\Async;
use app\core\mvc\Model;
use app\core\web\Response;

/**
 * Class Server
 * @package app\extend\net_ankio_tasker\core
 * Date: 2020/12/31 09:57
 * Author: ankio
 * Description:Tasker服务
 */
class Server extends Db
{

    private string $taskerUrl;

    public static string $table = "extend_lock";

    public function setDb()
    {
        parent::setDb();
        $this->execute(
            "CREATE TABLE  IF NOT EXISTS extend_lock(
              lock_time varchar(200)
            )"
        );
        $this->taskerUrl=Response::getAddress()."/tasker_server/";
        
    }
    

    /**
     * 定时任务路由，用于对定时任务进行路由
     * @return void
     */
    public  function route()
    {
        $split=explode("/",$_SERVER['REQUEST_URI']);
        if(sizeof($split)!==3)return;
        if($split[1]!=="tasker_server")return;
        Async::getInstance()->response();
        switch ($split[2]){
            case "init":$this->init();break;
        }

    }

    /**
     * 启动任务扫描服务
     * @return void
     */
    public function start(){
        if(!$this->isLock()){//没有锁定，请求保持锁定
            Async::getInstance()->request($this->taskerUrl."init","GET",[],[],"tasker_start");
        }
    }

    /**
     *  停止服务
     * @return void
     */
    public function stop(){
        $this->emptyTable("extend_lock");
    }


    /**
     *  服务启动与初始化
     * @return void
     */
    private function init()
    {
     //   $this->stop();
        do {
            $file = fopen(APP_TRASH."task.lock", "w+");
            flock($file, LOCK_EX ) or die("Can't lock");
            if(@is_file(APP_TRASH."task.end")){
                break;
            }
            Log::debug("tasker","10s pass....");
            $this->lock(time());//更新锁定时间
            //循环扫描
            Role::getInstance()->run();
            sleep(10);
            flock($file, LOCK_UN);
            fclose($file);
            if($this->isStop()){//间歇10秒后如果发现停止
                break;
            }

        } while(true);

        exitApp("服务退出，框架退出");
    }

    /**
     * 更新锁定时间
     * @param $time int 锁定时间
     * @return void
     */
    private function lock(int $time){

        $data= $this->select()->table("extend_lock")->limit(1)->commit();
        if(empty($data)){
             $this->insert()->keyValue(["lock_time"=>$time])->table("extend_lock")->commit();
        }else  $this->update()->set(["lock_time"=>$time])->table("extend_lock")->commit();
    }

    /**
     *  判断是否停止
     * @return bool
     */
    private function isStop(): bool
    {
        $data= $this->select()->table("extend_lock")->limit(1)->commit();
        if(empty($data))return false;
        return (time()-intval($data[0]['lock_time'])>20);
    }


    /**
     *  判断是否锁定
     * @return bool
     */
    private function isLock(): bool
    {
        $data= $this->select()->table("extend_lock")->limit(1)->commit();

        if(empty($data))return false;
        return (time()-intval($data[0]['lock_time'])<15);
    }
}