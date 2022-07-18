<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/
/**
 * Package: extend\ankioTask\core
 * Class Db
 * Created By ankio.
 * Date : 2022/5/5
 * Time : 13:01
 * Description :
 */

namespace extend\ankioTask\core;

use core\mvc\Model;

class Db extends Model
{
    public function setDb()
    {
        //手动设置默认数据库位置
        $this->setDbLocation(EXTEND_TASKER."data".DS, "db")->setDatabase("sqlite");
    }

}