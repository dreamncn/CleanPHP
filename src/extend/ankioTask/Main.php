<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\extend\ankioTask;

use app\extend\ankioTask\core\Server;
use app\core\event\EventListener;

define("EXTEND_TASKER",APP_EXTEND."ankioTask".DS);

class Main implements EventListener
{
    public function handleEvent($event,$msg)
    {
        $server=Server::getInstance();//获取对象实例
        $server->route();
        $server->start();//启动服务
    }
}
