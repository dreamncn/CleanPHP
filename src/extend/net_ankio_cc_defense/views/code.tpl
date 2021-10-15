<!--****************************************************************************
  * Copyright (c) 2020. CleanPHP. All Rights Reserved.
  ***************************************************************************-->

<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <title>请输入验证码</title>
    <style type="text/css">
        nav{background-position: 15px; text-align:center; position: fixed; top:30%; left:0; width: 100%; }
        .code{padding: 7px; font-size: 23px; }
        .clickmoi{border: 0; padding: 10px; font-size: 23px; border-radius:2px; color:white; background: blue; cursor: pointer; }
    </style>
</head>
<body>


<nav>
    <div style="text-align: center;">
        <h3>您的网络访问异常，需要输入验证码</h3>
        <form method="post" name="form" action="/check">
            <img height="130" width="390" src="/code.jpeg"><br>
            <input type="text" name="code" class="code" placeholder="请输入验证码.">
            <input type="button" class="clickmoi" onclick="go()" value="进行验证">
        </form>
    </div>
</nav>


</body>
<script type="text/javascript">function go(){ document.form.submit(); }</script>
</html>