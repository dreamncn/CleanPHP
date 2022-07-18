<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/

use core\event\EventManager;
use extend\rbac\Main;
//注册拓展运行位置
EventManager::attach("onControllerInit", Main::class);

