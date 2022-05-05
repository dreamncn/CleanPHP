<?php use app\core\mvc; if(!class_exists("app\\core\\mvc\\View", false)) exit("模板文件禁止被直接访问.");?>

<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <style >* {
        padding: 0;
        margin: 0;
    }

    div {
        padding: 4px 48px;
    }

    a {
        color: #2E5CD5;
        cursor: pointer;
        text-decoration: none
    }

    a:hover {
        text-decoration: underline;
    }

    body {
        background: #fff;
        color: #333;
        font-size: 18px;
    }

    h1 {
        font-size: 100px;
        font-weight: normal;
        margin-bottom: 12px;
    }

    p {
        line-height: 1.6em;
        font-size: 42px
    }</style>
    <title><?php echo htmlspecialchars($title, ENT_QUOTES, "UTF-8"); ?></title></head>
<body>
<div style="padding: 24px 48px;"><h1><?php echo htmlspecialchars($err, ENT_QUOTES, "UTF-8"); ?></h1>
    <p><span style="font-size:32px;"><?php echo htmlspecialchars($title, ENT_QUOTES, "UTF-8"); ?></span></p>
    <p><span style="font-size:25px;"><?php echo htmlspecialchars($msg, ENT_QUOTES, "UTF-8"); ?></span></p>
    <span id="jump_box" style="font-size:25px;">

    </span>
</div>
<script>
    let wait = "<?php echo htmlspecialchars($time, ENT_QUOTES, "UTF-8"); ?>";
    if (parseInt(wait) !== -1) {
        document.getElementById('jump_box').innerHTML = "还有<span id='jump'><?php echo htmlspecialchars($time, ENT_QUOTES, "UTF-8"); ?></span>秒为您自动跳转，<a href='<?php echo htmlspecialchars($url, ENT_QUOTES, "UTF-8"); ?>' target='_self'><?php echo htmlspecialchars($desc, ENT_QUOTES, "UTF-8"); ?></a>"
        setInterval(function () {
            document.getElementById("jump").innerText = (--wait).toString();
            if (wait <= 0) {
                location.href = "<?php echo htmlspecialchars($url, ENT_QUOTES, "UTF-8"); ?>";
            }
        }, 1000);
    } else if ("<?php echo htmlspecialchars($url, ENT_QUOTES, "UTF-8"); ?>" !== "") {
        document.getElementById('jump_box').innerHTML = "<span id='jump'><a href='<?php echo htmlspecialchars($url, ENT_QUOTES, "UTF-8"); ?>' target='_self'><?php echo htmlspecialchars($desc, ENT_QUOTES, "UTF-8"); ?></a></span>"
    }
</script>
</body>
</html>
