<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/
/**
 * Package: extend\ankioTask\core
 * Class ATasker
 * Created By ankio.
 * Date : 2022/6/4
 * Time : 09:35
 * Description :
 */

namespace extend\ankioTask\task;

use core\cache\Cache;
use lib\SSE\db\Tasker;

abstract class ATasker implements ITasker
{

    private string $taskId = "";//定时任务Id
    private string $taskName = "";
    public function __construct($taskId,$taskName)
    {
        $this->taskId  = $taskId;
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
        $data = Cache::init(0,APP_CACHE.DS."task".DS)->get($this->taskId);
        return $data=="";
    }

    public function stop(){
        Tasker::getInstance()->del($this->taskId);
        $this->onStop();
        exitApp("task stop");
    }


}