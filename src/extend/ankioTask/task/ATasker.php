<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/

/**
 * Package: app\extend\ankioTask\core
 * Class ATasker
 * Created By ankio.
 * Date : 2022/6/4
 * Time : 09:35
 * Description :
 */

namespace app\extend\ankioTask\task;

use app\core\cache\Cache;
use app\extend\ankioTask\core\Tasker;

abstract class ATasker
{

    private string $taskId = "";//定时任务Id
    private string $taskName = "";

    public function __construct($taskId, $taskName)
    {
        $this->taskId = $taskId;
        $this->taskName = $taskName;
    }

    /**
     * @return string
     */
    public function getTaskId(): string
    {
        return $this->taskId;
    }

    /**
     * @return string
     */
    public function getTaskName(): string
    {
        return $this->taskName;
    }


    public function isStop(): bool
    {
        $data = Cache::init(0, APP_CACHE . DS . "task" . DS)->get($this->taskId);
        return $data !== getmypid();
    }

    public function stop()
    {
        Tasker::getInstance()->del($this->taskId);
        exitApp("task stop");
    }

    public function onStart()
    {

    }

    public function onStop()
    {

    }

    public function onAbort($e)
    {

    }
}