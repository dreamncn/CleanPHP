<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/

use app\core\event\EventManager;
//注册拓展运行位置
EventManager::attach("beforeControllerRun", 'app\extend\rbac\Main');

