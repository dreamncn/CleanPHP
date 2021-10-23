<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

use app\core\core\Clean;
use app\core\event\EventManager;
if(!Clean::isConsole())
    EventManager::attach("afterFrameInit", 'app\lib\WebSocket\Main');

