(window.webpackJsonp=window.webpackJsonp||[]).push([[26],{346:function(t,s,v){"use strict";v.r(s);var e=v(3),_=Object(e.a)({},(function(){var t=this,s=t._self._c;return s("ContentSlotsDistributor",{attrs:{"slot-key":t.$parent.slotKey}},[s("div",{staticClass:"custom-block tip"},[s("p",{staticClass:"custom-block-title"},[t._v("提示")]),t._v(" "),s("p",[t._v("这里主要封装了"),s("code",[t._v("Reponse")]),t._v("工具类，用来处理一些响应的问题。")])]),t._v(" "),s("h2",{attrs:{id:"response-getaddress"}},[s("a",{staticClass:"header-anchor",attrs:{href:"#response-getaddress"}},[t._v("#")]),t._v(" Response::getAddress")]),t._v(" "),s("div",{staticClass:"custom-block tip"},[s("p",{staticClass:"custom-block-title"},[t._v("获取当前访问的URL域名")])]),t._v(" "),s("h2",{attrs:{id:"response-getrootdomain"}},[s("a",{staticClass:"header-anchor",attrs:{href:"#response-getrootdomain"}},[t._v("#")]),t._v(" Response::getRootDomain")]),t._v(" "),s("div",{staticClass:"custom-block tip"},[s("p",{staticClass:"custom-block-title"},[t._v("获取当前访问的根域名")])]),t._v(" "),s("h2",{attrs:{id:"response-getrootdomain-2"}},[s("a",{staticClass:"header-anchor",attrs:{href:"#response-getrootdomain-2"}},[t._v("#")]),t._v(" Response::getRootDomain")]),t._v(" "),s("div",{staticClass:"custom-block tip"},[s("p",{staticClass:"custom-block-title"},[t._v("获取域名")])]),t._v(" "),s("h2",{attrs:{id:"response-getnowaddress"}},[s("a",{staticClass:"header-anchor",attrs:{href:"#response-getnowaddress"}},[t._v("#")]),t._v(" Response::getNowAddress")]),t._v(" "),s("div",{staticClass:"custom-block tip"},[s("p",{staticClass:"custom-block-title"},[t._v("获取当前访问的地址")])]),t._v(" "),s("h2",{attrs:{id:"response-getmyip"}},[s("a",{staticClass:"header-anchor",attrs:{href:"#response-getmyip"}},[t._v("#")]),t._v(" Response::getMyIp")]),t._v(" "),s("div",{staticClass:"custom-block tip"},[s("p",{staticClass:"custom-block-title"},[t._v("获取服务器IP")])]),t._v(" "),s("h2",{attrs:{id:"response-msg"}},[s("a",{staticClass:"header-anchor",attrs:{href:"#response-msg"}},[t._v("#")]),t._v(" Response::msg")]),t._v(" "),s("div",{staticClass:"custom-block tip"},[s("p",{staticClass:"custom-block-title"},[t._v("跳转提示")])]),t._v(" "),s("table",[s("thead",[s("tr",[s("th",[t._v("参数")]),t._v(" "),s("th",[t._v("类型")]),t._v(" "),s("th",[t._v("解释")])])]),t._v(" "),s("tbody",[s("tr",[s("td",[t._v("$err")]),t._v(" "),s("td",[t._v("boolean")]),t._v(" "),s("td",[t._v("是否发生错误")])]),t._v(" "),s("tr",[s("td",[t._v("$code")]),t._v(" "),s("td",[t._v("int")]),t._v(" "),s("td",[t._v("响应代码")])]),t._v(" "),s("tr",[s("td",[t._v("$title")]),t._v(" "),s("td",[t._v("string")]),t._v(" "),s("td",[t._v("错误标题")])]),t._v(" "),s("tr",[s("td",[t._v("$msg")]),t._v(" "),s("td",[t._v("string")]),t._v(" "),s("td",[t._v("错误信息")])]),t._v(" "),s("tr",[s("td",[t._v("$time")]),t._v(" "),s("td",[t._v("int")]),t._v(" "),s("td",[t._v("跳转超时，设置为"),s("code",[t._v("-1")]),t._v("不跳转")])]),t._v(" "),s("tr",[s("td",[t._v("$url")]),t._v(" "),s("td",[t._v("string")]),t._v(" "),s("td",[t._v("跳转地址")])]),t._v(" "),s("tr",[s("td",[t._v("$desc")]),t._v(" "),s("td",[t._v("string")]),t._v(" "),s("td",[t._v("跳转描述")])])])]),t._v(" "),s("div",{staticClass:"custom-block warning"},[s("p",{staticClass:"custom-block-title"},[t._v("注意")]),t._v(" "),s("p",[t._v("采用"),s("RouterLink",{attrs:{to:"/00.开发文档/02.开始使用/01.框架架构.html#api模式"}},[s("code",[t._v("API")])]),t._v("方式构建网站的也可以使用该方法。")],1)]),t._v(" "),s("div",{staticClass:"custom-block note"},[s("p",{staticClass:"custom-block-title"},[t._v("笔记")]),t._v(" "),s("p",[t._v("跳转提示的 "),s("code",[t._v("tpl")]),t._v(" 模板位于 "),s("code",[t._v("src/static/innerView/tip/common.tpl")]),t._v(" ，可以自行修改。")])]),t._v(" "),s("h2",{attrs:{id:"response-location"}},[s("a",{staticClass:"header-anchor",attrs:{href:"#response-location"}},[t._v("#")]),t._v(" Response::location")]),t._v(" "),s("div",{staticClass:"custom-block tip"},[s("p",{staticClass:"custom-block-title"},[t._v("直接跳转不提示")])]),t._v(" "),s("table",[s("thead",[s("tr",[s("th",[t._v("参数")]),t._v(" "),s("th",[t._v("类型")]),t._v(" "),s("th",[t._v("解释")])])]),t._v(" "),s("tbody",[s("tr",[s("td",[t._v("$url")]),t._v(" "),s("td",[t._v("string")]),t._v(" "),s("td",[t._v("跳转地址")])]),t._v(" "),s("tr",[s("td",[t._v("$timeout")]),t._v(" "),s("td",[t._v("int")]),t._v(" "),s("td",[t._v("跳转超时，设置为"),s("code",[t._v("0")]),t._v("直接跳转")])])])]),t._v(" "),s("h2",{attrs:{id:"response-isinner"}},[s("a",{staticClass:"header-anchor",attrs:{href:"#response-isinner"}},[t._v("#")]),t._v(" Response::isInner")]),t._v(" "),s("div",{staticClass:"custom-block tip"},[s("p",{staticClass:"custom-block-title"},[t._v("是否为内网IP")])]),t._v(" "),s("table",[s("thead",[s("tr",[s("th",[t._v("参数")]),t._v(" "),s("th",[t._v("类型")]),t._v(" "),s("th",[t._v("解释")])])]),t._v(" "),s("tbody",[s("tr",[s("td",[t._v("$ip")]),t._v(" "),s("td",[t._v("string")]),t._v(" "),s("td",[t._v("需要判断的IP")])])])]),t._v(" "),s("h2",{attrs:{id:"response-gethttpschema"}},[s("a",{staticClass:"header-anchor",attrs:{href:"#response-gethttpschema"}},[t._v("#")]),t._v(" Response::getHttpSchema")]),t._v(" "),s("div",{staticClass:"custom-block tip"},[s("p",{staticClass:"custom-block-title"},[t._v("获取当前使用的协议，例如http://")])]),t._v(" "),s("h2",{attrs:{id:"response-setheader"}},[s("a",{staticClass:"header-anchor",attrs:{href:"#response-setheader"}},[t._v("#")]),t._v(" Response::setHeader")]),t._v(" "),s("div",{staticClass:"custom-block tip"},[s("p",{staticClass:"custom-block-title"},[t._v("设置响应头")])]),t._v(" "),s("table",[s("thead",[s("tr",[s("th",[t._v("参数")]),t._v(" "),s("th",[t._v("类型")]),t._v(" "),s("th",[t._v("解释")])])]),t._v(" "),s("tbody",[s("tr",[s("td",[t._v("$key")]),t._v(" "),s("td",[t._v("string")]),t._v(" "),s("td",[t._v("键名")])]),t._v(" "),s("tr",[s("td",[t._v("$name")]),t._v(" "),s("td",[t._v("string")]),t._v(" "),s("td",[t._v("键值")])])])])])}),[],!1,null,null,null);s.default=_.exports}}]);