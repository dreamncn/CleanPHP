<?php use app\core\mvc; if(!class_exists("app\\core\\mvc\\View", false)) exit("模板文件禁止被直接访问.");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo $msg; ?></title>
    <style type="">body {
            padding: 0;
            margin: 0;
            word-wrap: break-word;
            word-break: break-all;
            font-family: Courier, Arial, sans-serif;
            background: #EBF8FF;
            color: #5E5E5E;
        }

        div, h2, p, span {
            margin: 0;
            padding: 0;
        }

        ul {
            margin: 0;
            padding: 0;
            list-style-type: none;
            font-size: 0;
            line-height: 0;
        }

        #body {
            width: 918px;
            margin: 0 auto;
        }

        #main {
            width: 918px;
            margin: 13px auto 0 auto;
            padding: 0 0 35px 0;
        }

        #contents {
            width: 918px;
            float: left;
            margin: 13px auto 0 auto;
            background: #FFF;
            padding: 8px 0 0 9px;
        }

        #contents h2 {
            display: block;
            background: #CFF0F3;
            font: bold: 20px;
            padding: 12px 0 12px 30px;
            margin: 0 10px 22px 1px;
        }

        #contents ul {
            padding: 0 0 0 18px;
            font-size: 0;
            line-height: 0;
        }

        #contents ul li {
            display: block;
            padding: 0;
            color: #8F8F8F;
            background-color: inherit;
            font: normal 14px Arial, Helvetica, sans-serif;
            margin: 0;
        }

        #contents ul li span {
            display: block;
            color: #408BAA;
            background-color: inherit;
            font: bold 14px Arial, Helvetica, sans-serif;
            padding: 0 0 10px 0;
            margin: 0;
        }

        #oneborder {
            width: 800px;
            font: normal 14px Arial, Helvetica, sans-serif;
            border: #EBF3F5 solid 4px;
            margin: 0 30px 20px 30px;
            padding: 10px 20px;
            line-height: 23px;
        }

        #oneborder span {
            padding: 0;
            margin: 0;
        }

        #oneborder #current {
            background: #CFF0F3;
        }

        pre {

            white-space: pre-wrap;
        }
    </style>
</head>
<body>
<div id="main">
    <div id="contents">
        <pre><?php echo $dump; ?></pre>
        <h2>
            <pre><?php echo $msg; ?></pre>
        </h2>
        <?php $index = 0; ?>
        <?php if(!empty($array)){ $_foreach_trace_counter = 0; $_foreach_trace_total = count($array);?><?php foreach( $array as $trace ) : ?><?php $_foreach_trace_index = $_foreach_trace_counter;$_foreach_trace_iteration = $_foreach_trace_counter + 1;$_foreach_trace_first = ($_foreach_trace_counter == 0);$_foreach_trace_last = ($_foreach_trace_counter == $_foreach_trace_total - 1);$_foreach_trace_counter++;?>
            <ul>
                <li><span><?php echo htmlspecialchars($trace["file"], ENT_QUOTES, "UTF-8"); ?> on line <?php echo htmlspecialchars($trace["line"], ENT_QUOTES, "UTF-8"); ?> </span></li>
            </ul>
            <div id="oneborder">
                <?php if(!empty($trace["data"])){ $_foreach_singleLine_counter = 0; $_foreach_singleLine_total = count($trace["data"]);?><?php foreach( $trace["data"] as $singleLine ) : ?><?php $_foreach_singleLine_index = $_foreach_singleLine_counter;$_foreach_singleLine_iteration = $_foreach_singleLine_counter + 1;$_foreach_singleLine_first = ($_foreach_singleLine_counter == 0);$_foreach_singleLine_last = ($_foreach_singleLine_counter == $_foreach_singleLine_total - 1);$_foreach_singleLine_counter++;?>
                    <?php echo $singleLine; ?>
                <?php endforeach; }?>
            </div>
        <?php endforeach; }?>
    </div>
</div>
<div style="clear:both;padding-bottom:50px;"></div>
</body>
</html>