<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace controller\index;


use core\web\Response;


class Main extends BaseController
{


    public function index()
	{

         Response::msg(false,200,"CleanPHP","Welcome to use CleanPHP",-1,"https://github.com/dreamncn/CleanPHP","Github");
    }

}
