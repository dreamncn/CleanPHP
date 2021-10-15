<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
    <legend>文件上传处理类</legend>
</fieldset>

<blockquote class="layui-elem-quote"> 位置 /lib/Upload </blockquote>
<blockquote class="layui-elem-quote">
    <pre><code>进行上传相关操作
</code></pre>
    <h4>操作说明</h4>
    <ul>
        <li>$upload=new FileUpload();</li>
        <li>$upload->setOption("fileSize",1024);//设置私有成员属性，可以打开FileUpload查看所有私有成员</li>
        <li>$upload->upload($_Files);//成功返回true，失败返回false</li>
        <li>使用$upload->getErrorMsg()获取失败的信息</li>
    </ul>
</blockquote>
