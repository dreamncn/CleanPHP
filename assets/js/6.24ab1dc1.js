(window.webpackJsonp=window.webpackJsonp||[]).push([[6],{326:function(s,t,a){"use strict";a.r(t);var n=a(3),e=Object(n.a)({},(function(){var s=this,t=s._self._c;return t("ContentSlotsDistributor",{attrs:{"slot-key":s.$parent.slotKey}},[t("h2",{attrs:{id:"环境要求"}},[t("a",{staticClass:"header-anchor",attrs:{href:"#环境要求"}},[s._v("#")]),s._v(" 环境要求")]),s._v(" "),t("p",[s._v("PHP版本: "),t("Badge",{attrs:{text:">= 7.4",type:"tip",vertical:"middle"}})],1),s._v(" "),t("p",[s._v("Nginx版本： "),t("Badge",{attrs:{text:">= 1.18.0",type:"tip",vertical:"middle"}})],1),s._v(" "),t("h2",{attrs:{id:"配置运行目录"}},[t("a",{staticClass:"header-anchor",attrs:{href:"#配置运行目录"}},[s._v("#")]),s._v(" 配置运行目录")]),s._v(" "),t("div",{staticClass:"custom-block tip"},[t("p",{staticClass:"custom-block-title"},[s._v("提示")]),s._v(" "),t("p",[s._v("下面给出的是nginx配置。\n运行目录为 "),t("code",[s._v("程序路径/public")])])]),s._v(" "),t("div",{staticClass:"language-nginx line-numbers-mode"},[t("pre",{pre:!0,attrs:{class:"language-nginx"}},[t("code",[t("span",{pre:!0,attrs:{class:"token directive"}},[t("span",{pre:!0,attrs:{class:"token keyword"}},[s._v("root")]),s._v("\t\t\t/www/a.com/public")]),t("span",{pre:!0,attrs:{class:"token punctuation"}},[s._v(";")]),s._v("\n")])]),s._v(" "),t("div",{staticClass:"line-numbers-wrapper"},[t("span",{staticClass:"line-number"},[s._v("1")]),t("br")])]),t("h2",{attrs:{id:"伪静态配置"}},[t("a",{staticClass:"header-anchor",attrs:{href:"#伪静态配置"}},[s._v("#")]),s._v(" 伪静态配置")]),s._v(" "),t("div",{staticClass:"custom-block tip"},[t("p",{staticClass:"custom-block-title"},[s._v("提示")]),s._v(" "),t("p",[s._v("下面给出的是nginx配置，你也可以选择不配置伪静态，但是需要修改 "),t("code",[s._v("config/frame.yml")]),s._v(" 中 "),t("code",[s._v("rewrite")]),s._v(" 的值为 "),t("code",[s._v("false")])])]),s._v(" "),t("div",{staticClass:"language-nginx line-numbers-mode"},[t("pre",{pre:!0,attrs:{class:"language-nginx"}},[t("code",[t("span",{pre:!0,attrs:{class:"token directive"}},[t("span",{pre:!0,attrs:{class:"token keyword"}},[s._v("if")]),s._v(" ( "),t("span",{pre:!0,attrs:{class:"token variable"}},[s._v("$uri")]),s._v(" ~* "),t("span",{pre:!0,attrs:{class:"token string"}},[s._v('"^(.*)\\.php$"')]),s._v(")")]),s._v(" "),t("span",{pre:!0,attrs:{class:"token punctuation"}},[s._v("{")]),s._v("    \n"),t("span",{pre:!0,attrs:{class:"token directive"}},[t("span",{pre:!0,attrs:{class:"token keyword"}},[s._v("rewrite")]),s._v(" ^(.*) /index.php break")]),t("span",{pre:!0,attrs:{class:"token punctuation"}},[s._v(";")]),s._v("  \n"),t("span",{pre:!0,attrs:{class:"token punctuation"}},[s._v("}")]),s._v("\t\n\n"),t("span",{pre:!0,attrs:{class:"token directive"}},[t("span",{pre:!0,attrs:{class:"token keyword"}},[s._v("location")]),s._v(" /")]),s._v(" "),t("span",{pre:!0,attrs:{class:"token punctuation"}},[s._v("{")]),s._v("    \n"),t("span",{pre:!0,attrs:{class:"token directive"}},[t("span",{pre:!0,attrs:{class:"token keyword"}},[s._v("if")]),s._v(" (!-e "),t("span",{pre:!0,attrs:{class:"token variable"}},[s._v("$request_filename")]),s._v(")")]),t("span",{pre:!0,attrs:{class:"token punctuation"}},[s._v("{")]),s._v("      \n"),t("span",{pre:!0,attrs:{class:"token directive"}},[t("span",{pre:!0,attrs:{class:"token keyword"}},[s._v("rewrite")]),s._v(" (.*) /index.php")]),t("span",{pre:!0,attrs:{class:"token punctuation"}},[s._v(";")]),s._v("    \n"),t("span",{pre:!0,attrs:{class:"token punctuation"}},[s._v("}")]),s._v("  \n"),t("span",{pre:!0,attrs:{class:"token punctuation"}},[s._v("}")]),s._v("\n")])]),s._v(" "),t("div",{staticClass:"line-numbers-wrapper"},[t("span",{staticClass:"line-number"},[s._v("1")]),t("br"),t("span",{staticClass:"line-number"},[s._v("2")]),t("br"),t("span",{staticClass:"line-number"},[s._v("3")]),t("br"),t("span",{staticClass:"line-number"},[s._v("4")]),t("br"),t("span",{staticClass:"line-number"},[s._v("5")]),t("br"),t("span",{staticClass:"line-number"},[s._v("6")]),t("br"),t("span",{staticClass:"line-number"},[s._v("7")]),t("br"),t("span",{staticClass:"line-number"},[s._v("8")]),t("br"),t("span",{staticClass:"line-number"},[s._v("9")]),t("br")])]),t("h2",{attrs:{id:"修改域名"}},[t("a",{staticClass:"header-anchor",attrs:{href:"#修改域名"}},[s._v("#")]),s._v(" 修改域名")]),s._v(" "),t("div",{staticClass:"custom-block tip"},[t("p",{staticClass:"custom-block-title"},[s._v("提示")]),s._v(" "),t("p",[s._v("配置文件 /src/config/frame.yml，第三行，修改或添加即可。")])]),s._v(" "),t("div",{staticClass:"language-yml line-numbers-mode"},[t("pre",{pre:!0,attrs:{class:"language-yml"}},[t("code",[t("span",{pre:!0,attrs:{class:"token punctuation"}},[s._v("---")]),s._v("\n"),t("span",{pre:!0,attrs:{class:"token key atrule"}},[s._v("host")]),s._v(" "),t("span",{pre:!0,attrs:{class:"token punctuation"}},[s._v(":")]),s._v("\n  "),t("span",{pre:!0,attrs:{class:"token comment"}},[s._v("# 绑定域名")]),s._v("\n  "),t("span",{pre:!0,attrs:{class:"token punctuation"}},[s._v("-")]),s._v(" "),t("span",{pre:!0,attrs:{class:"token string"}},[s._v('"a.com"')]),s._v("\n  "),t("span",{pre:!0,attrs:{class:"token punctuation"}},[s._v("-")]),s._v(" "),t("span",{pre:!0,attrs:{class:"token string"}},[s._v('"localhost"')]),s._v("\n  "),t("span",{pre:!0,attrs:{class:"token punctuation"}},[s._v("-")]),s._v(" "),t("span",{pre:!0,attrs:{class:"token string"}},[s._v('"127.0.0.1"')]),s._v("\n")])]),s._v(" "),t("div",{staticClass:"line-numbers-wrapper"},[t("span",{staticClass:"line-number"},[s._v("1")]),t("br"),t("span",{staticClass:"line-number"},[s._v("2")]),t("br"),t("span",{staticClass:"line-number"},[s._v("3")]),t("br"),t("span",{staticClass:"line-number"},[s._v("4")]),t("br"),t("span",{staticClass:"line-number"},[s._v("5")]),t("br"),t("span",{staticClass:"line-number"},[s._v("6")]),t("br")])]),t("h2",{attrs:{id:"访问"}},[t("a",{staticClass:"header-anchor",attrs:{href:"#访问"}},[s._v("#")]),s._v(" 访问")]),s._v(" "),t("p",[s._v("现在你可以访问绑定的域名，开始使用CleanPHP框架。")]),s._v(" "),t("p",[t("img",{attrs:{src:"https://cdn.jsdelivr.net/gh/dreamncn/picBed@master/uPic/2022_05_04_19_48_32_1651664912_1651664912349_8dFixZ.png",alt:"img.png"}})])])}),[],!1,null,null,null);t.default=e.exports}}]);