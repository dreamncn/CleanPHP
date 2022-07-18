<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/
/**
 * Package: extend\ankioTask\task
 * Class ITasker
 * Created By ankio.
 * Date : 2022/7/3
 * Time : 20:44
 * Description :
 */

namespace extend\ankioTask\task;

interface ITasker
{
    public function onStart();
    public function onStop();

    public function onAbort($e);
}