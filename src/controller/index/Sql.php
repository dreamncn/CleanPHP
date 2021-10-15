<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\controller\index;

use app\extend\net_ankio_tasker\core\Tasker;
use app\core\config\Config;
use app\core\debug\Log;
use app\core\mvc\Model;
use app\core\web\Response;

class Sql extends BaseController
{

    public function init()
    {
        parent::init();

    }

    public function sqlinit()
    {
        $test=new \app\model\index\Test();
        $test->testInit();
    }

}
