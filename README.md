
# CleanPHP

## 项目简介&功能特性

​		CleanPHP是一套简洁、安全的PHP Web开发框架。CleanPHP的设计思想基于Android与Vue单页应用开发模式，融合了传统PHP框架开发方案，具有开发速度快、运行速度快、安全性可靠等优势。

## 文档

参考Wiki

## 快速上手

### 环境要求

PHP版本: 7.4

Nginx版本：1.18.0

Mysql数据库：8.0.19

### Nginx配置Public目录为运行目录：

```
root			/www/a.com/public;
```

### Nginx伪静态配置

```
if ( $uri ~* "^(.*)\.php$") {    
rewrite ^(.*) /index.php break;  
}	

location / {    
if (!-e $request_filename){      
rewrite (.*) /index.php;    
}  
}
```

### 修改域名

> 配置文件 /src/config/frame.yml，第三行，修改或添加即可。

```yml
---
host :
  # 绑定域名
  - "a.com"
  - "localhost"
  - "127.0.0.1"
```



## 开源协议

CleanPHP采用双授权机制。

如果您是个人站点开发请遵循MIT开源协议。如果你将开发的站点进行商用，请联系dream@dreamn.cn进行商用授权许可。







































