(window.webpackJsonp=window.webpackJsonp||[]).push([[11],{331:function(t,s,a){"use strict";a.r(s);var n=a(3),e=Object(n.a)({},(function(){var t=this,s=t._self._c;return s("ContentSlotsDistributor",{attrs:{"slot-key":t.$parent.slotKey}},[s("div",{staticClass:"custom-block warning"},[s("p",{staticClass:"custom-block-title"},[t._v("注意")]),t._v(" "),s("p",[t._v("如果您的机器无法配置伪静态，请将"),s("RouterLink",{attrs:{to:"/00.开发文档/02.开始使用/02.框架配置.html"}},[s("code",[t._v("src/config/frame.yml")])]),t._v("中"),s("code",[t._v("rewrite")]),t._v("值改为"),s("code",[t._v("false")]),t._v("。")],1),t._v(" "),s("p",[t._v("不配置伪静态的路由和配置了伪静态的路由长的很像。")])]),t._v(" "),s("div",{staticClass:"language- line-numbers-mode"},[s("pre",{pre:!0,attrs:{class:"language-text"}},[s("code",[t._v("# 配置伪静态\nhttp://localhost/index/main/test?id=1\nhttp://localhost/?id=1\nhttp://localhost/\n\n# 不配置伪静态\nhttp://localhost/?index/main/test?id=1\nhttp://localhost/??id=1\nhttp://localhost/\n\n# 主要区别就在于前面多了一个问号\n")])]),t._v(" "),s("div",{staticClass:"line-numbers-wrapper"},[s("span",{staticClass:"line-number"},[t._v("1")]),s("br"),s("span",{staticClass:"line-number"},[t._v("2")]),s("br"),s("span",{staticClass:"line-number"},[t._v("3")]),s("br"),s("span",{staticClass:"line-number"},[t._v("4")]),s("br"),s("span",{staticClass:"line-number"},[t._v("5")]),s("br"),s("span",{staticClass:"line-number"},[t._v("6")]),s("br"),s("span",{staticClass:"line-number"},[t._v("7")]),s("br"),s("span",{staticClass:"line-number"},[t._v("8")]),s("br"),s("span",{staticClass:"line-number"},[t._v("9")]),s("br"),s("span",{staticClass:"line-number"},[t._v("10")]),s("br"),s("span",{staticClass:"line-number"},[t._v("11")]),s("br")])]),s("div",{staticClass:"custom-block tip"},[s("p",{staticClass:"custom-block-title"},[t._v("提示")]),t._v(" "),s("p",[t._v("CleanPHP框架的路由配置，在"),s("code",[t._v("src/config/route.yml")]),t._v("文件里面。")])]),t._v(" "),s("h2",{attrs:{id:"路由设计注意"}},[s("a",{staticClass:"header-anchor",attrs:{href:"#路由设计注意"}},[t._v("#")]),t._v(" 路由设计注意")]),t._v(" "),s("ul",[s("li",[t._v("只允许 "),s("code",[t._v("英文字母（不区分大小写）")]),t._v("、"),s("code",[t._v("-")]),t._v("、"),s("code",[t._v(".")]),t._v("、"),s("code",[t._v("_")]),t._v("、"),s("code",[t._v("/")]),t._v("等字符。")]),t._v(" "),s("li",[t._v("映射关系应该是独一无二的，就是不允许两条不同的路由指向同一个控制器的同一个方法。\n例：")])]),t._v(" "),s("div",{staticClass:"language-yaml line-numbers-mode"},[s("pre",{pre:!0,attrs:{class:"language-yaml"}},[s("code",[s("span",{pre:!0,attrs:{class:"token key atrule"}},[t._v('"main"')]),t._v(" "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(":")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token string"}},[t._v('"index/main/test"')]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token key atrule"}},[t._v('"main_<div>"')]),t._v(" "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(":")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token string"}},[t._v('"index/main/test"')]),t._v("\n")])]),t._v(" "),s("div",{staticClass:"line-numbers-wrapper"},[s("span",{staticClass:"line-number"},[t._v("1")]),s("br"),s("span",{staticClass:"line-number"},[t._v("2")]),s("br")])]),s("p",[t._v("上面这种是错误的（都指向"),s("code",[t._v("index/main/test")]),t._v("），不能正常使用。")]),t._v(" "),s("p",[t._v("例：")]),t._v(" "),s("div",{staticClass:"language-yaml line-numbers-mode"},[s("pre",{pre:!0,attrs:{class:"language-yaml"}},[s("code",[s("span",{pre:!0,attrs:{class:"token key atrule"}},[t._v('"main"')]),t._v(" "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(":")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token string"}},[t._v('"index/main/empty"')]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token key atrule"}},[t._v('"main_<div>"')]),t._v(" "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(":")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token string"}},[t._v('"index/main/test"')]),t._v("\n")])]),t._v(" "),s("div",{staticClass:"line-numbers-wrapper"},[s("span",{staticClass:"line-number"},[t._v("1")]),s("br"),s("span",{staticClass:"line-number"},[t._v("2")]),s("br")])]),s("p",[t._v("这种才是对的。")]),t._v(" "),s("h2",{attrs:{id:"路由示例"}},[s("a",{staticClass:"header-anchor",attrs:{href:"#路由示例"}},[t._v("#")]),t._v(" 路由示例")]),t._v(" "),s("div",{staticClass:"language-yaml line-numbers-mode"},[s("pre",{pre:!0,attrs:{class:"language-yaml"}},[s("code",[s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("# id作为参数")]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token key atrule"}},[t._v('"index/main-api-<id>.asp"')]),t._v(" "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(":")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token string"}},[t._v('"index/main/api"')]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("# id作为参数")]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token key atrule"}},[t._v('"admin-<id>.html"')]),t._v(" "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(":")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token string"}},[t._v('"index/main/admin"')]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("# file作为参数")]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token key atrule"}},[t._v('"<file>.php"')]),t._v(" "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(":")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token string"}},[t._v('"index/main/test"')]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("# img作为参数")]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token key atrule"}},[t._v('"<img>.jpg"')]),t._v(" "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(":")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token string"}},[t._v('"index/test/img"')]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("# 隐藏模块访问路径")]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token key atrule"}},[t._v('"adminishide/<c>/<a>"')]),t._v(" "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(":")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token string"}},[t._v('"hide/<c>/<a>"')]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("#favicon.ico 重定向")]),t._v('\n"favicon.ico"'),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(":")]),s("span",{pre:!0,attrs:{class:"token string"}},[t._v('"index/main/forbid"')]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("# index页面路由（默认页面路由）")]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token key atrule"}},[t._v('""')]),t._v(" "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(":")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token string"}},[t._v('"index/main/index"')]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("# 默认路由方案")]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token key atrule"}},[t._v('"<m>/<c>/<a>"')]),t._v(" "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(":")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token string"}},[t._v('"<m>/<c>/<a>"')]),t._v("\n")])]),t._v(" "),s("div",{staticClass:"line-numbers-wrapper"},[s("span",{staticClass:"line-number"},[t._v("1")]),s("br"),s("span",{staticClass:"line-number"},[t._v("2")]),s("br"),s("span",{staticClass:"line-number"},[t._v("3")]),s("br"),s("span",{staticClass:"line-number"},[t._v("4")]),s("br"),s("span",{staticClass:"line-number"},[t._v("5")]),s("br"),s("span",{staticClass:"line-number"},[t._v("6")]),s("br"),s("span",{staticClass:"line-number"},[t._v("7")]),s("br"),s("span",{staticClass:"line-number"},[t._v("8")]),s("br"),s("span",{staticClass:"line-number"},[t._v("9")]),s("br"),s("span",{staticClass:"line-number"},[t._v("10")]),s("br"),s("span",{staticClass:"line-number"},[t._v("11")]),s("br"),s("span",{staticClass:"line-number"},[t._v("12")]),s("br"),s("span",{staticClass:"line-number"},[t._v("13")]),s("br"),s("span",{staticClass:"line-number"},[t._v("14")]),s("br"),s("span",{staticClass:"line-number"},[t._v("15")]),s("br"),s("span",{staticClass:"line-number"},[t._v("16")]),s("br")])]),s("p",[t._v("上面展示了几种常见的URL配置方案。简而言之，框架会将一切用"),s("code",[t._v("<")]),t._v("和"),s("code",[t._v(">")]),t._v("包裹的名称都作为"),s("strong",[t._v("变量")]),t._v("处理。")]),t._v(" "),s("p",[t._v("其中，"),s("code",[t._v("m")]),t._v("是模块，"),s("code",[t._v("c")]),t._v("是控制器，"),s("code",[t._v("a")]),t._v("是执行方法，这三个是固定的，"),s("strong",[t._v("不允许")]),t._v("被覆盖的。")]),t._v(" "),s("div",{staticClass:"custom-block warning"},[s("p",{staticClass:"custom-block-title"},[t._v("注意")]),t._v(" "),s("p",[t._v("路由匹配是有优先级的，按照从上到下的顺序依次匹配，如果优先匹配到就不会继续匹配后面的路由。")]),t._v(" "),s("p",[t._v("所以编写路由的时候可以按照"),s("code",[t._v("<a><m><c>")]),t._v("参数的数量进行排序。")])]),t._v(" "),s("h2",{attrs:{id:"路由函数url"}},[s("a",{staticClass:"header-anchor",attrs:{href:"#路由函数url"}},[t._v("#")]),t._v(" 路由函数"),s("code",[t._v("url")])]),t._v(" "),s("div",{staticClass:"custom-block tip"},[s("p",{staticClass:"custom-block-title"},[t._v("用于生成新的URL")])]),t._v(" "),s("table",[s("thead",[s("tr",[s("th",{staticStyle:{"text-align":"center"}},[t._v("参数名")]),t._v(" "),s("th",{staticStyle:{"text-align":"center"}},[t._v("类型")]),t._v(" "),s("th",{staticStyle:{"text-align":"center"}},[t._v("描述")])])]),t._v(" "),s("tbody",[s("tr",[s("td",{staticStyle:{"text-align":"center"}},[t._v("$m")]),t._v(" "),s("td",{staticStyle:{"text-align":"center"}},[t._v("string")]),t._v(" "),s("td",{staticStyle:{"text-align":"center"}},[t._v("模块名")])]),t._v(" "),s("tr",[s("td",{staticStyle:{"text-align":"center"}},[t._v("$c")]),t._v(" "),s("td",{staticStyle:{"text-align":"center"}},[t._v("string")]),t._v(" "),s("td",{staticStyle:{"text-align":"center"}},[t._v("控制器名")])]),t._v(" "),s("tr",[s("td",{staticStyle:{"text-align":"center"}},[t._v("$a")]),t._v(" "),s("td",{staticStyle:{"text-align":"center"}},[t._v("string")]),t._v(" "),s("td",{staticStyle:{"text-align":"center"}},[t._v("方法")])]),t._v(" "),s("tr",[s("td",{staticStyle:{"text-align":"center"}},[t._v("$param")]),t._v(" "),s("td",{staticStyle:{"text-align":"center"}},[t._v("array")]),t._v(" "),s("td",{staticStyle:{"text-align":"center"}},[t._v("参数数组")])])])])])}),[],!1,null,null,null);s.default=e.exports}}]);