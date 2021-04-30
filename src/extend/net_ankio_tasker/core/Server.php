<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\extend\net_ankio_tasker\core;


use app\vendor\debug\Log;
use app\vendor\mvc\Model;
use app\vendor\web\Response;

/**
 * +----------------------------------------------------------
 * Class Server
 * +----------------------------------------------------------
 * @package app\extend\net_ankio_tasker\core
 * +----------------------------------------------------------
 * Date: 2020/12/31 09:57
 * Author: ankio
 * +----------------------------------------------------------
 * Desciption:Tasker服务
 * +----------------------------------------------------------
 */
class Server extends Model
{

    private static $instance=null;

    private $taskerUrl;

    public function __construct()
    {
        parent::__construct("extend_lock");
        $this->setDbLocation(EXTEND_TASKER."data".DS, "db");
        $this->setDatabase("sqlite");
        $this->execute(
        "CREATE TABLE  IF NOT EXISTS extend_lock(
                    lock_time varchar(200)
                    )"
    );
        $this->taskerUrl=Response::getAddress()."/tasker_server/";
        //任务URL
    }

    /**
     * +----------------------------------------------------------
     * 获取对象实例
     * +----------------------------------------------------------
     * @return Server
     * +----------------------------------------------------------
     */
    public static function getInstance(){
        return self::$instance===null?(self::$instance=new Server()):self::$instance;
    }

    /**
     * +----------------------------------------------------------
     * 定时任务路由，用于对定时任务进行路由
     * +----------------------------------------------------------
     * @return void
     * +----------------------------------------------------------
     */
    public  function route()
    {
        $splite=explode("/",$_SERVER['REQUEST_URI']);



        if(sizeof($splite)!==3)return;

        if($splite[1]!=="tasker_server")return;

        Async::response(0);

        switch ($splite[2]){
            case "init":$this->init();break;
        }

    }

    /**
     * +----------------------------------------------------------
     * 启动任务扫描服务
     * +----------------------------------------------------------
     * @return void
     * +----------------------------------------------------------
     */
    public function start(){
        if(!$this->isLock()){//没有锁定，请求保持锁定
            Log::debug("Tasker","定时任务好久没有访问了。所以我就尝试发起锁定！");
            $bool=Async::request($this->taskerUrl."init","GET",[],[],"tasker_start");
            Log::debug("Tasker","定时任务服务启动。");
        }
    }

    /**
     * +----------------------------------------------------------
     *  停止服务
     * +----------------------------------------------------------
     * @return void
     * +----------------------------------------------------------
     */
    public function stop(){
        self::getInstance()->emptyTable("extend_lock");
    }


    /**
     * +----------------------------------------------------------
     *  服务启动与初始化
     * +----------------------------------------------------------
     * @return void
     * +----------------------------------------------------------
     */
    private function init()
    {

        $fp=fopen(EXTEND_TASKER."tasker_server.lock","w+");
        if(!flock($fp,LOCK_EX)){
            Log::debug("Tasker","无法锁定...");
            fclose($fp);
            return;
        }
        Log::debug("Tasker","文件已经锁定...");
        //通过文件指针锁定，避免重复拉起服务。
     //   $this->stop();


        do {
            $this->lock(time());//更新锁定时间
            //循环扫描
            Tasker::getInstance()->run();
            Log::debug("Tasker","循环扫描中...");
            sleep(10);
            if(!$this->isTimeOut()){
                sleep(10);
            }

            if($this->isStop()){//间歇10秒后如果发现停止
                Log::debug("Tasker","进程退出...");
                break;
            }
        } while(true);
        flock($fp, LOCK_UN);
        fclose($fp);
        exitApp("服务退出，框架退出");
    }

    /**
     * +----------------------------------------------------------
     * 更新锁定时间
     * +----------------------------------------------------------
     * @param $time int 锁定时间
     * +----------------------------------------------------------
     * @return void
     * +----------------------------------------------------------
     */
    private function lock($time){
        self::getInstance()->update()->set(["lock_time"=>$time])->table("extend_lock")->commit();
    }

    /**
     * +----------------------------------------------------------
     *  判断是否停止
     * +----------------------------------------------------------
     * @return bool
     * +----------------------------------------------------------
     */
    private function isStop(){
        $data=self::getInstance()->select()->table("extend_lock")->limit(1)->commit();
        if(empty($data))return false;
        return (time()-intval($data[0]['lock_time'])>20);
    }
    private function isTimeOut(){
        $data=self::getInstance()->select()->table("extend_lock")->limit(1)->commit();
        if(empty($data))return true;
        return (time()-intval($data[0]['lock_time'])>10);
    }

    /**
     * +----------------------------------------------------------
     *  判断是否锁定
     * +----------------------------------------------------------
     * @return bool
     * +----------------------------------------------------------
     */
    private function isLock(){
        $data=self::getInstance()->select()->table("extend_lock")->limit(1)->commit();
        if(empty($data))return false;
        return (time()-intval($data[0]['lock_time'])<15);
    }
}