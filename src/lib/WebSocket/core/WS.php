<?php
/*******************************************************************************
 * Copyright (c) 2021. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\lib\WebSocket\core;

use app\core\debug\Log;
use Swoole;
class WS
{
    protected $token;
    protected $data;
    protected $ser;
    protected $fp;
    /**
     * @param $ser Swoole\WebSocket\Server
     * @param $fp int
     * @param $msg
     */
    public static function _err_router(Swoole\WebSocket\Server $ser, int $fp, $msg)
    {
        if($ser->isEstablished($fp)){
            $ser->push($fp,rawurlencode(json_encode(["msg"=>$msg])));
        }

        Log::info("websocket","websocket_$fp:".$msg);
    }

    public  function __construct(Swoole\WebSocket\Server $ser,$fp,$data,$token){
        $this->fp=$fp;
        $this->ser=$ser;
        $this->data=$data;
        $this->token=$token;
    }

    /**
     * 主动关闭websocket
     * @param $fp int
     */
    public function closeConnect($fp){
        $this->ser->close($fp);
        //主动关闭链接
        Log::info("websocket_controller","服务器主动关闭websocket");
    }

    /**
     *
     * @param $fp int
     */
    public function onClose(int $fp){

    }

    public function ret($type,$data){
        global $__module, $__controller, $__action;
        return json_encode(["type"=>$type,"m"=>$__module,"a"=>$__action,"c"=>$__controller,"data"=>$data]);
    }

    public function init(){
        return null;
    }
}