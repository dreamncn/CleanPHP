<!--****************************************************************************
  * Copyright (c) 2020. CleanPHP. All Rights Reserved.
  ***************************************************************************-->


<!DOCTYPE html>
<html  lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>欢迎使用CleanPHP开发框架</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">

    <link rel="stylesheet" href="/layui/css/layui.css">
    <link rel="stylesheet" href="/custom/css/layui.css">
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">

    <div class="layui-logo">CleanPHP开发框架</div>
    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
            <ul class="layui-nav layui-nav-tree" >
                <li class="layui-nav-item layui-nav-itemed">
                    <a class="" href="<{url('index','main','index')}>">框架简介</a>
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:">内容输出</a>
                    <dl class="layui-nav-child">
                        <dd><a href="<{url('index','example','no_fun')}>">无方法输出模板</a></dd>
                        <dd><a href="<{url('index','example','fun_def')}>">有方法默认输出模板</a></dd>
                        <dd><a href="<{url('index','example','fun_no')}>">有方法但不输出</a></dd>
                        <dd><a href="<{url('index','example','out_text')}>">直接输出文本</a></dd>
                        <dd><a href="<{url('index','example','out_define')}>">输出指定模板</a></dd>
                        <dd><a href="<{url('index','example','inner')}>">内置响应输出拦截</a></dd>
                        <dd><a href="<{url('index','example','out_html')}>">输出html转义</a></dd>
                        <dd><a href="<{url('index','example','dump')}>">调试输出</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item"><a href="<{url('index','example','input',['id'=>1,'is'=>false,'qq'=>"00000000"])}>">内容输入</a></li>
                <li class="layui-nav-item">
                    <a href="javascript:">数据库操作</a>
                    <dl class="layui-nav-child">
                        <dd><a href="<{url('index','sql','sqlinit')}>">增删改查</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:;">权限控制</a>
                    <dl class="layui-nav-child">
                        <dd><a href="<{url('hide','main','index')}>">后台访问（未授权）</a></dd>
                        <dd><a href="<{url('hide','main','index',['isAuth'=>true])}>">后台访问（已授权）</a></dd>
                        <dd><a href="<{url('hide','main','route',['isAuth'=>true])}>">路由安全</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:;">插件列表</a>
                    <dl class="layui-nav-child">
                        <dd><a href="<{url('index','plugin','cc')}>">CC攻击防御</a></dd>
                        <dd><a href="<{url('index','plugin','tasker')}>">定时任务</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:;">拓展工具类</a>
                    <dl class="layui-nav-child">
                        <dd><a href="<{url('index','lib','rsa')}>">RSA与AES加密解密</a></dd>
                        <dd><a href="<{url('index','lib','upload')}>">文件上传处理类</a></dd>
                        <dd><a href="<{url('index','lib','csrf')}>">SSRF与CSRF防御</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:;">功能测试</a>
                    <dl class="layui-nav-child">
                        <dd><a href="<{url('index','test','who',['id'=>1])}>">SQL注入测试</a></dd>
                        <dd><a href="<{url('index','test','xss',['str'=>'hello'])}>">XSS测试</a></dd>
                        <dd><a href="<{url('index','test','ssrf',['url'=>'http://www.baidu.com/'])}>">SSRF测试</a></dd>
                        <dd><a href="<{url('index','test','upload')}>">文件上传于CSRF测试</a></dd>
                    </dl>
                </li>
            </ul>
        </div>
    </div>

    <div class="layui-body">
        <!-- 内容主体区域 -->
        <div style="padding: 15px;">
            <{include file=$__template_file}>
        </div>
        <div class="layui-main custom-footer">
            <!-- 底部固定区域 -->
            <p>&copy; <{date("Y")}> <a href="/">ankio.net</a> MIT license</p>
        </div>
    </div>


</div>
<script src="/layui/layui.js" charset="utf-8"></script>
<script>
    //JavaScript代码区域
    layui.use('element', function(){
        var element = layui.element;
        element.on('nav(demo)', function (elem) {
            //console.log(elem)
            layer.msg(elem.text());
        });
    });
</script>
<!--template_file_script-->
</body>
</html>
