<!--****************************************************************************
  * Copyright (c) 2021. CleanPHP. All Rights Reserved.
  ***************************************************************************-->

<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
    <legend>WebSocket支持</legend>
</fieldset>
<blockquote class="layui-elem-quote"> 此处只演示了最简单的websokcet实现 </blockquote>

<form class="layui-form" action="">
    <div class="layui-form-item">
        <label class="layui-form-label">账户</label>
        <div class="layui-input-inline">
            <input type="text" name="name" required  lay-verify="required" placeholder="请输入账户" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">密码</label>
        <div class="layui-input-inline">
            <input type="password" name="passwd" required lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
        </div>

    </div>


    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="formDemo">登录</button>
            <button type="button" id="logout" class="layui-btn layui-btn-primary">退出登录</button>
        </div>
    </div>
</form>
<div class="layui-form-item">
    <div class="layui-input-block">
        <button class="layui-btn" id="read">读取用户信息（只有登录成功才行）</button>
    </div>
</div>

<div class="layui-form-item layui-form-text">
    <label class="layui-form-label">输出结果</label>
    <div class="layui-input-block">
        <div  id="textarea" class="layui-textarea"></div>
    </div>
</div>




<!--include_start-->
<script src="/custom/websocket.js"></script>
<script>
    //JavaScript代码区域
    layui.use(['layer','form'], function(){
        const layer = layui.layer;
        const form = layui.form;
       // cleanLine();
        ws.createSocket('wss://cleanphp.ankio.net/ws/');
        ws.eventClose=function () {
            console.log("链接断开");//后台不间断发送数据，持续接收。
        }
        ws.eventMessage=function (data) {
            let d=decodeURIComponent(data.data);
            console.log(d);//后台不间断发送数据，持续接收。
            let js = null;
            try{
                js = JSON.parse(d);
            }catch (e) {
                console.log(e)
            }
            if(js!=null){
                let url=js['m']+"/"+js['c']+"/"+js['a'];
                let type = js['type'];
                let data=js["data"];
                switch (url){
                    case "index/main/login":
                        if(type==="login"){
                           if(data["msg"]==="success"){
                               localStorage.setItem("token",data["token"]);
                               addLine("登录成功")
                           }else{
                               addLine(data["msg"])
                           }
                        }else{
                            addLine(data["msg"])
                        }
                        break;
                    case "index/main/getList":
                        if(type==="login"){
                            addLine("用户数据获取失败！"+data["msg"])
                        }else{
                            addLine("用户数据获取信息成功:"+data["msg"])
                        }
                        break;
                    default:
                        addLine("服务器消息:"+data["msg"])
                }
            }else{
                addLine(d)
            }


        }
        const send = function (m,  c,a, body) {
            const token = localStorage.getItem("token");
            const js = {'m': m, 'a': a, 'c': c, 'token': token, 'body': body};
            ws.push(JSON.stringify(js))
        };

        const addLine = function (msg){
            const text = layui.$("#textarea").html();
            layui.$("#textarea").html(text+"<br>"+msg)
        }
        const cleanLine = function (){
            layui.$("#textarea").html('')
        }
//监听提交
        layui.$("#read").on("click",function (e) {
            send("index","main","getList",null)
            addLine("请求用户数据中...")
        })
        layui.$("#logout").on("click",function (e) {
            send("index","main","logout",null)
            addLine("正在请求退出登录...")
        })
        form.on('submit(formDemo)', function(data){
            send("index","main","login",data.field)
            addLine("发送登录数据:"+JSON.stringify(data.field))
            return false;
        });
        //layer.msg(elem.text());
    });


</script>
<!--include_end-->