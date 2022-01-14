<?php
/*******************************************************************************
 * Copyright (c) 2021. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\controller\index;


use app\lib\Upload\FileUpload;
use app\lib\URL\DefenseAgainstCSRF;
use app\lib\URL\DefenseAgainstSSRF;
use app\core\web\Cookie;
use app\core\web\Request;
use app\core\web\Response;
use app\core\web\Session;

class Test extends BaseController
{
    function who(){
       $id=arg("id",1,true,"int");
       $test=new \app\model\index\Test();
       dump($id);
       dump($test->get($id));
       return "";
    }

    function xss(){
        return arg("str","hi",true,"str");
    }

    function ssrf(){
        $s=new DefenseAgainstSSRF();
        $s->setJmpLimit(2);//允许两次跳转
        $s->setTimeout(10);//10秒超时
        $url=urldecode(arg("url","http://www.baidu.com/",true,"str"));
        if($s->verifySSRFURL($url)){
            dump(file_get_contents($url));
        }else{
            dump("$url    " .$s->getErr());
        }
    }
    function upload(){
        Session::getInstance()->start();
        $csrf=new DefenseAgainstCSRF();

        if(Request::isPost()){
            dump($_SESSION);
            $this->_auto_display=false;
            dump("csrfToken(Cookie):".Cookie::getInstance()->get("csrftoken"));
            dump("csrfToken(Session):".Session::getInstance()->get("csrftoken"));
            if(!$csrf->verifyCSRFToken()){
                dump("csrf校验失败");
                return null;
            }
            $upload=new FileUpload();
            $bool = $upload->upload("file");
            if($bool){
                $str=$upload->getFileName();
                $path = $upload->getFilePath();
                dump("上传的文件名".$str);
                dump("存储路径".$path);
                $this->setEncode(false);
                return "<img src='".url('index','test','img',['img'=>$str])."'>";
            }else{
                dump("上传失败".$upload->getErrorMsg());
                $str=$upload->getFileName();
                $path = $upload->getFilePath();
                dump("上传的文件名".$str);
                dump("存储路径".$path);
            }

        }else{
            $csrf->setCSRFToken(Session::getInstance()->Id());
        }
        return null;
    }

    function Img(){
        $upload=new FileUpload();
        header("Content-type: image/jpeg");
        $this->setEncode(false);
        return $upload->getFile(arg("img"));
    }
}