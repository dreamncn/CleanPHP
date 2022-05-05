<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/
/**
 * Package: app\extend\ankioTask\core
 * Class Db
 * Created By ankio.
 * Date : 2022/5/5
 * Time : 13:01
 * Description :
 */

namespace app\extend\ankioTask\core;

use app\core\mvc\Model;

class Db extends Model
{
    public function setDb()
    {
        //手动设置默认数据库位置
        $this->setDbLocation(EXTEND_TASKER."data".DS, "db")->setDatabase("sqlite");
    }

}