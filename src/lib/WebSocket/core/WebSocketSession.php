<?php
/*******************************************************************************
 * Copyright (c) 2021. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\lib\WebSocket\core;

use app\core\cache\Cache;
use app\core\mvc\Model;

class WebSocketSession extends Model
{
    /**
     * @var WebSocketSession
     */
    private static $instance=null;
    private $fp=0;
    public static function getInstance()
    {
        return (self::$instance==null)?self::$instance=new WebSocketSession():self::$instance;

    }
    public function __construct()
    {
        parent::__construct("session_websocket");
        $this->setDatabase("sqlite_websocket");
    }

    function start($fp,$m){
        $this->fp=$fp;
        $data=$this->select()->where(["name like :id",":id"=>"websocket_$this->fp%"])->commit();
        if(empty($data)){
            self::set("websocket_m",$m);
        }
    }

    function set($key,$value){
        $value = serialize($value);
        $name = 'websocket_'.$this->fp.$key;
        $data=$this->select()->where(["name"=>$name])->commit();
        if(empty($data)) {
            $this->insert(SQL_INSERT_NORMAL)->keyValue(["name"=>$name,"value"=>$value])->commit();
        }else{
            $this->update()->where(["name"=>$name])->set(["value"=>$value])->commit();
        }
    }
    function get($key){
        $name = 'websocket_'.$this->fp.$key;
        $data=$this->select()->where(["name"=>$name])->commit();
        if(empty($data))return null;
        return unserialize($data[0]["value"]);
    }
    function destroy(){
        $this->delete()->where(["name like :id",":id"=>"websocket_$this->fp%"])->commit();
      //  Cache::del("websocket_".$this->fp);
    }
    function getId(){
        return "websocket_".$this->fp;
    }

}