<?php
/*******************************************************************************
 * Copyright (c) 2022. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\controller\index;

use app\core\web\Response;
use app\core\web\Verity;

class Example extends BaseController
{
	public function fun_def()
	{
	    //这里可以做一些操作，默认会查找example_fun_def.tpl输出
	}

	public function fun_no()
	{
		return "";//返回空就是不输出
	}

	public function out_text()
	{
	    $this->setEncode(false);//如果需要输出html，将转义关闭，否则默认转义
		return "直接输出文本 <a href='".url("index","main","index")."'>返回主页</a>";
	}

    public function out_html()
    {

        return "输出一段html,他会以转义形势出现 ->  <a href='".url("index","main","index")."'>返回主页</a>";
    }

    public function inner(){

	    Response::msg(false,200,"内置拦截","hi，这是内置拦截。 内置拦截器的可以有很多写法，比如时间为0，就直接跳转。时间为-1，就不跳转。你还可以在static/innerView/tip文件夹下建立200.tpl、404.tpl来使用不同的状态代码模板进行输出 ",60,url("index","main","index"),"返回主页");
    }

	public function out_define(){
	    return $this->display("define");//输出指定模板
    }

    public function dump(){
	    //dump函数不受输出转义控制
	    $array=["hello"=>["hahahah",0,"222"=>["3",0]],"王二",1,"3"=>false];
	    dump($array,true);
    }

    public function input(){
	    $id=arg("id",-1,true,"int");
	    $bool=arg("is",true,true,"bool");
        $qq=arg("qq","",true,"str");

        dump("qq $qq is $bool id $id");
        $verity=Verity::get($qq);
        $verity->check(8,0);
        dump("qq校验结果：".$verity->getErr());


    }

}
