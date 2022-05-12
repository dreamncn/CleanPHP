
![](https://cdn.jsdelivr.net/gh/dreamncn/picBed@master/uPic/2022_05_04_13_33_55_1651642435_1651642435229_EuTStm.png)

## 项目简介&功能特性

​		CleanPHP是一套简洁、安全的PHP Web开发框架。CleanPHP的设计思想基于Android与Vue单页应用开发模式，融合了传统PHP框架开发方案，具有开发速度快、运行速度快、安全性可靠等优势。

## 文档

[wiki](https://cleanphp.ankio.net/)

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

MIT开源协议





































