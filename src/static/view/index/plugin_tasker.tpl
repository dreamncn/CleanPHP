<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
    <legend>定时任务</legend>
</fieldset>

<blockquote class="layui-elem-quote"> 位置 /extend/net_ankio_tasker</blockquote>
<blockquote class="layui-elem-quote">
    <pre><code>Tasker服务是一种基于nginx多线程原理的定时任务工具。他通过后台重新拉起一个http后台连接来启动tasker服务，该服务通过10s轮询sqlite数据库实现定时任务执行。
</code></pre>
    <h5>文件说明</h5>


    <ul>
        <li>data/db.yml 拓展所需的数据库信息，默认使用的sqlite3，可以自行修改配置文件使用mysql之类的</li>
        <li>data/data.db sqlite3数据库文件</li>
        <li>core/Tasker.php 定时任务类</li>
        <li>core/Async.php 后台服务类</li>
    </ul>
</blockquote>

<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
    <legend>功能演示</legend>
</fieldset>

<blockquote class="layui-elem-quote"><a href="<{url('index','main','tasker')}>">访问此页面以添加定时任务</a> </blockquote>