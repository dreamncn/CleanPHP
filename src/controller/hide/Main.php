<?php
/*******************************************************************************
 * Copyright (c) 2020. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\controller\hide;



class Main extends BaseController
{
	public function index()
	{

		dump([
            '当前页面的访问url为：'.url("hide","main","index"),
            "真实路径为 /hide/main/index 这是由于目录/config/route.yml的路由表重新定义了访问方法，该功能可以起到隐藏真实后台入口的功能。你可以直接访问这个路径试试。"
        ],true);
	}

	public function route(){
	    dump([
	        "正常生成路由"=>url("index","main","index1"),
            "主页路由"=>url("index","main","index"),
            "混淆视听"=>url("index","main","api",["id"=>236]),
            "混淆视听2"=>url("index","main","test",["file"=>"23333"]),
            "混淆视听3"=>url("index","main","admin",["id"=>6]),
            "影藏真实入口"=>url("hide","main","index"),
            "其他路由方案可自行配置"
        ],true);
    }

}
