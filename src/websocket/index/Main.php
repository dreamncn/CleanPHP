<?php
/*******************************************************************************
 * Copyright (c) 2021. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\websocket\index;

use app\lib\WebSocket\core\WebSocketSession;

class Main extends BaseWebsocket
{
    public function Login(){
        if($this->token!=null&&WebSocketSession::getInstance()->get("token")==$this->token){
            return $this->ret("login",["msg"=>"failed! you had login!"]);
        }
        $name = $this->data["name"];
        $passwd = $this->data["passwd"];
        if($name=="admin"&&$passwd=="admin"){
            $token=md5($name.$passwd);
            WebSocketSession::getInstance()->set("token",$token);
          return $this->ret("login",["msg"=>"success","token"=>$token]);
        }else{
         return $this->ret("login",["msg"=>"failed! username or password invalid!"]);
        }
       // $this->closeConnect($this->fp);
    }

    public function getList(){
        return $this->ret("admin",["msg"=>"hi,ankio! Good Afternoon!"]);
    }
}