<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/

use app\core\event\EventManager;
use app\extend\ankioTask\Main;

const EXTEND_RBAC = APP_EXTEND . "rbac" . DS;
//订阅事件
EventManager::attach("onFrameInit", Main::class);

