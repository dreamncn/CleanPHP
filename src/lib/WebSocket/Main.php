<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\lib\WebSocket;


use app\core\config\Config;
use app\core\debug\Log;
use app\core\event\EventListener;

define("EXTEND_WEBSOCKET",APP_LIB."WebSocket".DS);

class Main implements EventListener
{

    /**
     * @throws \Exception
     */
    public function handleEvent($event, $msg)
    {
      //  $this->route();
        if(!$this->isStart()){
            $cmd= "nohup php ".EXTEND_WEBSOCKET."core/start.php > ".APP_LOG."websocket.log 2>&1 &";
            try {
                shell_exec($cmd);
            }catch (\Exception $e){
                throw new \Exception("请允许执行shell_exec函数。");
            }
        }
    }
    
    private function isStart(){
        $port =  Config::getInstance("frame")->getOne("websocket_port");
        $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_nonblock($sock);
        socket_connect($sock,"127.0.0.1", $port);
        socket_set_block($sock);
        $r = [$sock];
        $w = array($sock);
        $f = array($sock);
        $return = @socket_select($r, $w, $f, 3);
        socket_close($sock);
        return $return==1;
    }

}
