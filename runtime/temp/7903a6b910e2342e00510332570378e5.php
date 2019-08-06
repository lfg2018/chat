<?php /*a:1:{s:58:"E:\wamp64\stu\chat\application\index\view\index\index.html";i:1565075588;}*/ ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="format-detection" content="telephone=no" />
    <title>沟通中</title>
    <link rel="stylesheet" type="text/css" href="/static/newcj/css/themes.css?v=2017129">
    <link rel="stylesheet" type="text/css" href="/static/newcj/css/h5app.css">
    <link rel="stylesheet" type="text/css" href="/static/newcj/fonts/iconfont.css?v=2016070717">
    <script src="/static/newcj/js/jquery.min.js"></script>
    <script src="/static/newcj/js/dist/flexible/flexible_css.debug.js"></script>
    <script src="/static/newcj/js/dist/flexible/flexible.debug.js"></script>
</head>
<body ontouchstart>
<div class='fui-page-group'>
<div class='fui-page chatDetail-page'>
    <div class="chat-header flex">
        <i class="icon icon-toleft t-48"></i>
        <span class="shop-titlte t-30">商店</span>
        <span class="shop-online t-26"></span>
        <span class="into-shop">进店</span>
    </div>
    <div class="fui-content navbar" style="padding:1.2rem 0 1.35rem 0;">
        <div class="chat-content">
            <p style="display: none;text-align: center;padding-top: 0.5rem" id="more"><a>加载更多</a></p>
            <p class="chat-time"><span class="time">2017-11-12</span></p>
        </div>
    </div>
    <div class="fix-send flex footer-bar">
        <i class="icon icon-emoji1 t-50"></i>
        <input class="send-input t-28" maxlength="200">
        <i class="icon icon-add t-50" style="color: #888;"></i>
        <span class="send-btn">发送</span>
    </div>
</div>
</div>

<script>

    var fromid = <?php echo htmlentities($fromid); ?>;
    var toid = <?php echo htmlentities($toid); ?>;
     var ws =  new WebSocket("ws://127.0.0.1:8282");

      ws.onmessage = function(e){
          var message = eval("("+e.data+")");
          switch (message.type){
              case "init":
                  var bild = '{"type":"bind","fromid":"'+fromid+'"}';
                  ws.send(bild);
                  return;
              case "text":
                $('.chat-content').append('<div class="chat-text section-left flex">\n' +
                    '            <span class="char-img" style="background-image: url(/static/newcj/img/123.jpg)"></span>\n' +
                    '            <span class="text"><i class="icon icon-sanjiao4 t-32"></i>'+message.data+'</span>\n' +
                    '            </div>');
              return;
          }
         }

     $(".send-btn").click(function(){
         var text = $(".send-input").val();
         var message = '{"data":"'+text+'","type":"say","fromid":"'+fromid+'","toid":"'+toid+'"}';
         $('.chat-content').append('<div class="chat-text section-right flex">\n' +
             '        <span class="text"><i class="icon icon-sanjiao3 t-32"></i>'+text+'</span>\n' +
             '        <span class="char-img" style="background-image: url(/static/newcj/img/132.jpg)"></span>\n' +
             '        </div>');
         ws.send(message);

         $(".send-input").val("");
     })


</script>
</body>
</html>
