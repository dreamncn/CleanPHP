<!--****************************************************************************
  * Copyright (c) 2020. CleanPHP. All Rights Reserved.
  ***************************************************************************-->

<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <title>Loading...</title>
    <style type="text/css">
        .loading {display: flex; flex-direction: column; align-items: center; }
        .loading__msg {
            font-family: SansSerif, serif; font-size: 16px; }
        .loading__dots {display: flex; flex-direction: row; width: 100%; justify-content: center; margin: 100px 0 30px 0; }
        .loading__dots__dot {background-color: #44BBA4; width: 20px; height: 20px; border-radius: 50%; margin: 0 5px; color: #587B7F; }
        .loading__dots__dot:nth-child(1) {animation: bounce 1s 1s infinite; }
        .loading__dots__dot:nth-child(2) {animation: bounce 1s 1.2s infinite; }
        .loading__dots__dot:nth-child(3) {animation: bounce 1s 1.4s infinite; } @keyframes bounce {0% {transform: translate(0, 0); } 50% {transform: translate(0, 15px); } 100% {transform: translate(0, 0); } }
    </style>
</head>
<body>


<div class="loading" style="margin-top: 11%;">
    <div class="loading__dots">
        <div class="loading__dots__dot"></div>
        <div class="loading__dots__dot"></div>
        <div class="loading__dots__dot"></div>
    </div>
    <div class="loading__msg">
        <div style="text-align: center;">
            <b style="font-size: 22px;">
                <span style="color: black;">浏览器安全检查中,<span id="jump_box"></span></span>


            </b>
        </div></div>
</div>


</body>
<script>
    let wait = "<{$time}>";
    document.getElementById('jump_box').innerHTML = "还有<span id='jump'><{$time}></span>秒..."
    setInterval(function () {
        document.getElementById("jump").innerText = --wait;
    }, 1000);
</script>
</html>