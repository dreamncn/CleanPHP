<?php
/*******************************************************************************
 * Copyright (c) 2021. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\websocket\index;

use app\core\cache\Cache;
use app\core\debug\Log;
use app\lib\WebSocket\core\WebSocketSession;
use app\lib\WebSocket\core\WS;

class BaseWebsocket extends WS
{

    public function init(){
        //检测是否登录
        global $__module, $__controller, $__action;
        if($__controller!="main"||$__action!="login"){
            $this->ser->push($this->fp,WebSocketSession::getInstance()->get("token"));
            $this->ser->push($this->fp,$this->token);
            if($this->token==null||WebSocketSession::getInstance()->get("token")!=$this->token){
                return $this->ret("login",["msg"=>"please login！"]);
            }
        }
        return null;
    }
}