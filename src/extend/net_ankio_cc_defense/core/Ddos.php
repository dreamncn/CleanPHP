<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/
namespace app\extend\net_ankio_cc_defense\core;
use app\core\config\Config;
use app\core\debug\Log;
use app\core\web\Request;
use app\core\web\Response;
use app\core\web\Session;

class Ddos
{
    private $config;
    private $timeout;
    private $query;
    private $action;
    private $expire;
    public function __construct()
    {
        $this->config=Config::getInstance("config")->setLocation(EXTEND_CC_DEFENSE."data".DS);
        $this->timeout=intval($this->config->getOne("jump"));
        $this->query=intval($this->config->getOne("query"));
        $this->action=$this->config->getOne("action");
        $this->expire=intval($this->config->getOne("expire"));
    }

    /**
     * 启动检查
     * @return void
     */
    public function start(){

        Session::getInstance()->start();//开启session
        $this->check();
    }

    /**
     * 检查基本信息
     * @return void
     */
    private function check(){
     //   if(Response::isInner(Request::getClientIP()))return;//内网访问不检测
        $session=Session::getInstance();
        $record=Record::getInstance();
        if($session->get("ddos_standby")===null){
            $session->set("ddos_standby",1);
            $record->add($session->Id(),0,0,time());
           // $timeout=$this->config->getOne("jump");
            Response::location(Response::getNowAddress(),$this->timeout,false);
            exitApp("cc攻击检查中...","start",EXTEND_CC_DEFENSE."views",["time"=>$this->timeout]);
        }else{
            $session->set("ddos_standby",$session->get("ddos_standby")+1);
            $data=$record->get($session->id());
           // Log::debug("plugin",print_r($data,true));
            if($data==null){
                $session->delete("ddos_standby");
                exitApp("cc攻击检查未通过...");
            }

            //如果仍在违规的阶段
            if(intval($data["times"])>=$this->query){
                $record->udp($session->Id(),["url"=>Response::getNowAddress()]);
                $this->security($data);
            }

            $sec=time()-intval($data["last_time"]);



            if($sec<=1){
                $record->udp($session->Id(),["times = times + 1"]);
                if(intval($data["times"])+1>=$this->query){
                    //违规次数+1
                    $record->udp($session->Id(),
                        [
                            "count = count + 1",
                            "check_in"=>1,
                            "url"=>Response::getNowAddress()
                        ]);

                    $data["count"]=intval($data["count"])+1;
                    $data["check_in"]=1;
                    $this->security($data);

                }
            }
            $record->udp($session->Id(),["last_time"=>time()]);
        }
    }

    /**
     * 检测到疑似发生违规行为
     * @param $data
     * @return void
     */
    private function security($data){
      //  dump($data,true);
        //进入违规检查

        switch ($this->action){
            case "ban":$this->banIp($data);break;
            case "code":$this->code($data);break;
            default:{
                $arr=explode(">",$this->action);
                if(sizeof($arr)<2){
                    $this->banIp($data);
                }else{
                    $this->code($data,intval($arr[1]));
                }

            }
        }
    }

    /**
     * 封禁IP
     * @param $data
     * @return void
     */
    private function banIp($data)
    {
        $ipData=BanIP::getInstance()->get();
        if($ipData==null&&intval($data["check_in"])===0){
            //封禁期已过
            Record::getInstance()->udp(Session::getInstance()->Id(),["times=0","count = 0"]);

            Response::location($data["url"],$this->timeout,false);
            exitApp("cc攻击封禁IP解封...","start",EXTEND_CC_DEFENSE."views",["time"=>$this->timeout]);
        }elseif(intval($data["check_in"])===1){
            Log::debug("clean","Ip封禁：".Request::getClientIP());
            //进行封禁
            Record::getInstance()->udp(Session::getInstance()->Id(),["check_in"=>0]);

            $count=intval($data["count"])==0?1:$data["count"];
            BanIP::getInstance()->add($this->expire*$count);
            //仍然封禁
        }
        Response::msg(true,403,"403 Forbidden","您当前没有权限访问该资源",-1);
    }

    /**
     * 验证码验证
     * @param $data
     * @param int $times
     * @return void
     */
    private function code($data,$times=0)
    {
        if($times!==0&&intval($data["count"])>=$times){
            $this->banIp($data);
        }
        //判断是否为请求IMG_URL
        if($_SERVER['REQUEST_URI']==="/code.jpeg"){
            Code::getImg();
            exitApp("验证码生成。");
        }elseif($_SERVER['REQUEST_URI']==="/check"){
            if(Code::check()){
                //检查通过
                Record::getInstance()->udp(Session::getInstance()->Id(),["times = 0","count = 0"]);

                Response::location($data["url"],$this->timeout,false);
                exitApp("cc攻击封禁IP解封...","start",EXTEND_CC_DEFENSE."views",["time"=>$this->timeout]);
            }else{
                Record::getInstance()->udp(Session::getInstance()->Id(),["count = count + 1"]);
                Response::location(Response::getAddress(),0,true);
            }
        }else{
            exitApp("cc攻击验证码...","code",EXTEND_CC_DEFENSE."views");
        }
    }


}