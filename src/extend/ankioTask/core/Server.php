<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace extend\ankioTask\core;


use core\cache\Cache;
use core\debug\Log;
use core\extend\Async\Async;
use core\web\Response;
use extend\ankioTask\task\ATasker;

/**
 * Class Server
 * @package extend\net_ankio_tasker\core
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
        $split=explode("/",Response::getUrl());
        Log::debug("tasker",print_r($split,true));
        if(sizeof($split)!==3)return;
        if($split[1]!=="tasker_server")return;
        Async::getInstance()->response();
        $GLOBALS["frame_log_tag"] = "task_";
        Log::debug("tasker","事件{$split[2]}");
        switch ($split[2]){
            case "task_request":$this->request();break;
            case "init":$this->init();break;
        }

    }

    /**
     * 启动任务扫描服务
     * @return void
     */
    public function start(){
        if(Cache::init(20)->get("task")==""){//没有锁定，请求保持锁定
            Async::getInstance()->request($this->taskerUrl."init","GET",[],[],"tasker_start");
        }
    }


    /**
     *  服务启动与初始化
     * @return void
     */
    private function init()
    {
        do {
            Log::debug("tasker","10s pass....");
            $this->lock();//更新锁定时间
            //循环扫描
            Tasker::getInstance()->run();
            sleep(10);
            if(!$this->isLock())
                break;

        } while(true);
        Cache::init()->del("task");
        exitApp("服务退出，框架退出");
    }

    /**
     * 更新锁定时间
     * @return void
     */
    private function lock(){
        Cache::init(20)->set("task",getmypid());
   }



    /**
     *  判断是否锁定
     * @return bool
     */
    private function isLock(): bool
    {
       return  Cache::init(20)->get("task") === getmypid();
    }

    /**
     * 任务执行
     * @return void
     */
    private function request()
    {

        $id = arg("task_id");
        $name = arg("task_name");
        $cls = arg("task_url");
        $task = new $cls($id,$name);
        $pid = getmypid();
        //任务Id
        Cache::init(0,APP_CACHE.DS."task".DS)->set($id,$pid);
        try{
            /**
             * @var  ATasker $task
             */
            @$task->onStart();
        }catch (\Throwable $e){
            $task->onAbort($e);
        }
        Cache::init(0,APP_CACHE.DS."task".DS)->del($id);
        $task->onStop();
        exitApp("服务退出，框架退出");
    }
}