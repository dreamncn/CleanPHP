<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\controller\index;

use app\extend\net_ankio_tasker\core\Tasker;
use app\vendor\config\Config;
use app\vendor\debug\Log;
use app\vendor\mvc\Model;
use app\vendor\web\Response;

class Main extends BaseController
{
	public function index()
	{

	}

	public function admin()
	{
        Response::msg(true, 200, "混淆视听3", "收到参数 id=".arg("id",null), -1, '/', '回到首页');
	}

	public function test()
	{
		Response::msg(true, 200, "混淆视听", "收到参数 file=".arg("file",null), -1, '/', '回到首页');
	}

    public function api()
    {
        Response::msg(true, 200, "混淆视听2", "收到参数 id=".arg("id",null), -1, '/', '回到首页');
    }

	public function tasker(){
	    $tasker=Tasker::getInstance();
	    $tasker->clean();
        dump("定时任务1,立刻执行,执行2次");
	    $tasker->add($tasker->cycleNMinute(0),url('index','tasker','tasker_start_1',["info"=>"ankio 666"]),"write_0_2",2);
        dump("定时任务2,每隔1分钟执行,无执行次数限制");
        $id=$tasker->add($tasker->cycleNMinute(1),url('index','tasker','tasker_start_2',["info"=>"ankio 2333"]),"write_1_-1");
        dump("定时任务3,每隔2分钟执行,执行3次");
        $tasker->add($tasker->cycleNMinute(2),url('index','tasker','tasker_start_3',["id"=>$id]),"write_2_3",3);



        dump("定时任务添加完成，请打开日志文件（storage/logs/今天日期/tasker.log）查看定时任务执行情况");
    }


}
