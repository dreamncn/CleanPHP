<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\controller\index;

use app\core\debug\Log;
use app\lib\Async\Async;

class Tasker extends BaseController
{
    public function init(){
        //响应tasker任务，并对URL进行校验
        Async::getInstance()->response();
    }
    public function tasker_start_1(){
         }
    public function tasker_start_2(){
           }
    public function tasker_start_3(){
        $id = arg("id",-1,false,"int");//在定时任务2执行了>=2次以后关闭它
        $times = \app\extend\ankioTask\core\Tasker::getInstance()->getTimes($id);
       if($times>=2){
           \app\extend\ankioTask\core\Tasker::getInstance()->del($id);
          }


    }
}