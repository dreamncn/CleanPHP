# CC攻击防御拓展

    主要用于防御CC攻击与爬虫，实际上对机器性能还是有损的，不能百分百降低CC攻击的消耗，只能在一定程度上减少消耗，避免高并发带来的一些服务崩溃。

# 文件说明
   
   - data/config.yml 拓展的一些配置
   - data/db.yml 拓展所需的数据库信息，默认使用的sqlite3，可以自行修改配置文件使用mysql之类的
   - data/data.db sqlite3数据库文件
   - views/start.tpl 防御检测页面
   - views/code.tpl 验证码输入页面
   
# 基本信息

- Ver 1.0
- Powered by Ankio
