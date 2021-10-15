<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\controller\index;


use app\extend\net_ankio_tasker\core\Async;
use app\core\debug\Log;

class Tasker extends BaseController
{
    public function init(){

        //响应tasker任务，并对URL进行校验
        Async::response(0);
    }
    public function tasker_start_1(){
        Log::debug("Tasker","write_0_2 -> 任务1 立刻执行 执行次数 2");
    }
    public function tasker_start_2(){
        Log::debug("Tasker","write_1_-1 -> 任务2 每隔1分钟 无执行次数限制");
    }
    public function tasker_start_3(){
        Log::debug("Tasker","write_2_3 -> 任务3 每隔2分钟 执行次数 3");
        $id=arg("id",-1,false,"int");//在定时任务2执行了>=2次以后关闭它
        Log::debug("Tasker","任务ID".$id);
       $times = \app\extend\net_ankio_tasker\core\Tasker::getInstance()->getTimes($id);

       if($times>=2){
           \app\extend\net_ankio_tasker\core\Tasker::getInstance()->del($id);
           Log::debug("Tasker","write_2_3[任务内容] -> 把定时任务2关了");
       }


    }
}