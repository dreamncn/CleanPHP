<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

use app\core\event\EventManager;
//注册拓展运行位置
EventManager::attach("afterFrameInit", 'app\extend\net_ankio_cc_defense\Main');

