<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/

use app\core\event\EventManager;
use app\extend\rbac\Main;
//注册拓展运行位置
EventManager::attach("onControllerInit", Main::class);

