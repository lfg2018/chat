<?php /*a:1:{s:58:"E:\wamp64\stu\chat\application\index\view\index\index.html";i:1565340131;}*/ ?>
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
        <input type="file" name="pic" id="file" style="display: none"/>
        <i class="icon icon-add t-50 image_up " style="color: #888;"></i>
        <span class="send-btn">发送</span>
    </div>
</div>
</div>

<script>

    var fromid = <?php echo htmlentities($fromid); ?>;
    var toid = <?php echo htmlentities($toid); ?>;
    var from_head = '';
    var to_head = '';
    var to_name = '';
    var online = 0;
    var API_URL = 'http://test.chat.com/index.php/api/chat/'
     var ws =  new WebSocket("ws://127.0.0.1:8282");

      ws.onmessage = function(e){
          var message = eval("("+e.data+")");
          switch (message.type){
              case "init":
                  var bild = '{"type":"bind","fromid":"'+fromid+'"}';
                  ws.send(bild);
                  get_head(fromid,toid);
                  get_name(toid);
                  get_record();
                  var onlineJson = '{"type":"online","fromid":"'+fromid+'","toid":"'+toid+'"}';
                  ws.send(onlineJson);
                  return;
              case "text":
                  if(toid == message.fromid) {
                      appendOtherHtml(message.data,1);
                      $(".chat-content").scrollTop(3000);
                  }
                  return;
              case "save":
                  save_msg(message);
                  if(message.isread == 1){
                      online = 1;
                      $('.shop-online').text('在线');
                  }else{
                      online = 0;
                      $('.shop-online').text('不在线');
                  }
                  return;
              case "online":
                  if(message.status == 1){
                      online = 1;
                     $('.shop-online').text('在线');
                  }else{
                      online = 0;
                      $('.shop-online').text('不在线');
                  }
                  return;
              case "say_img":
                  appendOtherHtml(message.data,2);
                  $(".chat-content").scrollTop(3000);
                  return;
          }
         }

     $(".send-btn").click(function(){
         var text = $(".send-input").val();
         var message = '{"data":"'+text+'","type":"say","fromid":"'+fromid+'","toid":"'+toid+'"}';
         appendSelfHtml(text,1);
         $(".chat-content").scrollTop(3000);
         ws.send(message);
         $(".send-input").val("");
     })

    function save_msg(msg) {
        $.post(
            API_URL+"saveMessage",msg,function () {},'json'
        )
    }

    $('.image_up').click(function () {
        $('#file').click();
    })

    $('#file').change(function () {
        formdata = new FormData();
        formdata.append('fromid',fromid);
        formdata.append('toid',toid);
        formdata.append('online',online);
        formdata.append('file',$('#file')[0].files[0]);
        $.ajax({
            url:API_URL+'uploadImg/',
            type:'POST',
            data:formdata,
            dataType:'json',
            processData:false,
            contentType:false,
            success:function (data) {
                 $('#file').val("");
                 if(data.status == 'ok'){
                     appendSelfHtml(data.img_name,2);
                     $(".chat-content").scrollTop(3000);
                     var message = '{"data":"\\'+data.img_name+'","type":"say_img","fromid":"'+fromid+'","toid":"'+toid+'"}';
                     $("#file").val("");
                     ws.send(message);
                 }else{
                     console.log(data);
                 }
            }
        })
        
    })

    function get_head(fromid,toid){
        $.post(
            API_URL+'getHead',
            {"fromid":fromid,"toid":toid},
            function (e) {
                from_head = e.from_head.headimgurl;
                to_head = e.to_head.headimgurl
            },"json"
        )
    }

    function get_name(toid) {
        $.post(
            API_URL+'getName',
            {'uid':toid},
            function(e){
                to_name = e.nickname
                $(".shop-titlte").text("与"+to_name+"聊天中...");
            }
        )
    }
    
    function get_record() {
        $.post(
            API_URL+'getRecode',
            {"fromid":fromid,"toid":toid},
            function (e) {
                $.each(e,function (index,content) {
                    if(fromid == content.fromid){
                        appendSelfHtml(content.content,content.type);
                    }else{
                        appendOtherHtml(content.content,content.type);
                    }
                })
                $(".chat-content").scrollTop(3000);
            },"json"
    );
    }

    function appendSelfHtml(text,type) {
        if(type == 2){
            text = '<img width="120em"   src="'+text+'">';
        }
        $('.chat-content').append('<div class="chat-text section-right flex">\n' +
            '        <span class="text"><i class="icon icon-sanjiao3 t-32"></i>'+text+'</span>\n' +
            '        <span class="char-img" style="background-image: url('+from_head+')"></span>\n' +
            '        </div>');
    }

    function appendOtherHtml(text,type) {
        if(type == 2){
            text = '<img width="120em"  src="'+text+'">';
        }
        $('.chat-content').append('<div class="chat-text section-left flex">\n' +
            '            <span class="char-img" style="background-image: url('+to_head+')"></span>\n' +
            '            <span class="text"><i class="icon icon-sanjiao4 t-32"></i>'+text+'</span>\n' +
            '            </div>');
    }


</script>
</body>
</html>
