<?php
/*******************************************************************************
 * Copyright (c) 2021. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\lib\WebSocket\core;
use Swoole;
class WebSocket
{
    private $server;
    public function __construct($port)
    {
        $this->server = new Swoole\WebSocket\Server("127.0.0.1", $port);
        $this->server->set([
            "heartbeat_check_interval" => 60
        ]);
        $this->server->on('open', function (Swoole\WebSocket\Server $server, $request) {
           $this->onReceiveConnect($server, $request);
            //WebSocket验证
           // $this->server->close($request->fd);
        });
        $this->server->on('message', function (Swoole\WebSocket\Server $server, $frame) {
            if($frame->data=="i"){//心跳
                $server->push($frame->fd, "i");
                return;
            }
            $this->onMessage($server,$frame->fd,$frame->data,$frame->opcode);
        });
        $this->server->on('close', function ($ser, $fd) {
           // echo "client {$fd} closed\n";
            $this->onCloseConnect($ser,$fd);
        });

        $this->server->start();
    }

    private function includeApp(){
        $_SERVER['CLEAN_CONSOLE'] = true;
        $_SERVER["HTTP_HOST"] = "localhost";
        $_SERVER["REQUEST_URI"] = 'websocket';
        include_once __DIR__."/../../../public/index.php";
    }

    private function onReceiveConnect(Swoole\WebSocket\Server $server, $request){
        $this->includeApp();
        WebSocketObject::Run($server,null,null,'open');
    }
    private function onCloseConnect($ser,$fd){
        $this->includeApp();
        WebSocketObject::Run($ser,$fd,null,'close');
    }
    private function onMessage($server,$fd,$data,$opcode){
        $this->includeApp();
        WebSocketObject::Run($server,$fd,$data,'msg');
    }
}