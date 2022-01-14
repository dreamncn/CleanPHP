<?php
/*******************************************************************************
 * Copyright (c) 2021. CleanPHP. All Rights Reserved.
 ******************************************************************************/

namespace app\controller\index;


use app\lib\Encryption\AESEncryptHelper;
use app\lib\Encryption\RSAEncryptHelper;
use app\lib\URL\DefenseAgainstCSRF;
use app\lib\URL\DefenseAgainstSSRF;
use app\core\mvc\Controller;

class Lib extends Controller
{
    function rsa(){

        dump("AES 加密解密演示");
        $key=getRandom(32);
        $aes = new AESEncryptHelper($key);
        $key = $aes-> createSecretKey($key);
        dump("加密密钥：$key");
        $output = $aes-> encryptWithOpenssl("hello world");
        dump("加密结果 $output");
        $output = $aes-> decryptWithOpenssl($output);
        dump("解密结果 $output");

        dump("RSA 加密解密签名演示");
        $rsa=new RSAEncryptHelper();
        $rsa->create();//创建公钥私钥
        dump("RSA 创建公钥私钥");
        dump($rsa->getKey());
        dump("RSA 私钥加密（签名）");
        $output=$rsa->rsaPrivateEncrypt("hello word");
        dump("加密结果 $output");
        dump("RSA 公钥解密");
        $output=$rsa->rsaPublicDecrypt($output);
        dump("解密结果 $output");
        dump("RSA 公钥加密（加解密）");
        $output=$rsa->rsaPublicEncrypt("hello word");
        dump("加密结果 $output");
        dump("RSA 私钥解密");
        $output=$rsa->rsaPrivateDecrypt($output);
        dump("解密结果 $output");
    }

    function csrf(){
        $session=getRandom(32);//随机找个session，真实环境中请使用Session类进行管理
        $csrf=new DefenseAgainstCSRF();
        $token=$csrf->setCSRFToken($session);
        //表单页面应该调用该方法
        dump("现在可以在cookie中看见csrftoken：$token");
        dump("表单提交时需要附带该token,并在接收处理函数那里使用verifyCSRFToken函数进行处理");
        $bool=$csrf->verifyCSRFToken();
        dump("当前csrf校验结果：".($bool===true?"true":"false"));

        $ssrf=new DefenseAgainstSSRF();
        dump("SSRF防御是对准备进行内部访问的url进行校验");
        dump("访问https://baidu.com");
        $bool=$ssrf->verifySSRFURL("https://baidu.com");
        dump("当前ssrf校验结果：".$ssrf->getErr());
        dump("访问https://192.168.2.1");
        $bool=$ssrf->verifySSRFURL("https://192.168.2.1");
        dump("当前ssrf校验结果：".$ssrf->getErr());
        dump("访问http://a.com");
        $bool=$ssrf->verifySSRFURL("http://a.com");
        dump("当前ssrf校验结果：".$ssrf->getErr());

    }

}