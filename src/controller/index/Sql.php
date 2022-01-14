<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\controller\index;

use app\core\debug\Log;

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
