<?php
/*******************************************************************************
 * Copyright (c) 2021. CleanPHP. All Rights Reserved.
 ******************************************************************************/


use app\core\config\Config;
use app\core\debug\Log;
use app\lib\WebSocket\core\WebSocket;

$_SERVER['CLEAN_CONSOLE'] = true;
$_SERVER["HTTP_HOST"] = "localhost";
$_SERVER["REQUEST_URI"] = 'websocket';
include_once __DIR__."/../../../public/index.php";
$port =  Config::getInstance("frame")->getOne("websocket_port");
Log::info("websocket","listen at 127.0.0.1:$port");
new WebSocket(intval($port));
