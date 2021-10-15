<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
    <legend>CC攻击防御</legend>
</fieldset>

<blockquote class="layui-elem-quote"> 位置 /extend/net_ankio_cc_defense </blockquote>
<blockquote class="layui-elem-quote">
    <pre><code>主要用于防御CC攻击与爬虫，实际上对机器性能还是有损的，不能百分百降低CC攻击的消耗，只能在一定程度上减少消耗，避免高并发带来的一些服务崩溃。
</code></pre>
    <h5>文件说明</h5>
    <ul>
        <li>data/config.yml 拓展的一些配置</li>
        <li>data/db.yml 拓展所需的数据库信息，默认使用的sqlite3，可以自行修改配置文件使用mysql之类的</li>
        <li>data/data.db sqlite3数据库文件</li>
        <li>views/start.tpl 防御检测页面</li>
        <li>views/code.tpl 验证码输入页面</li>
    </ul>
</blockquote>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
    <legend>功能演示</legend>
</fieldset>

<blockquote class="layui-elem-quote">该插件默认为打开状态，直接访问样例站点即可看到效果(本地部署无效，加入了内网检测，如果需要本地查看效果，请手动注释该文件第49行extend/net_ankio_cc_defense/core/Ddos.php)，快速刷新也可以看到效果。 </blockquote>