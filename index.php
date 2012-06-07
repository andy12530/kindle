<!DOCTYPE HTML>
<html lang="en-US">
<head>
<meta charset="UTF-8">
<title>Kindle 阅读</title>
<style type="text/css">
body{
    margin:0;
    font-family:"Lucida Grande",Verdana,"Times New Roman",Tahoma, Arial,Helvetica,sans-serif;
}
header{
    width:100%;
    background-color:black;
    border-bottom:1px solid #bbb;
}
#logo{
    width: 840px;
    margin: 0 auto;
    height: 40px;
}

section{
    background-color:#0067C8;
    min-height:600px;
    padding:1px;
    background:-webkit-radial-gradient(circle, #B7274F, #AA0030)
}
article{
    width:860px;
    margin:120px auto 10px auto;
    overflow:hidden;
}
#pic{
    height:360px;
    width:500px;
    border-radius:8px;
    background-image:url("image/kindle.png");
    display: inline-block;
    float:left;
}
div.form{
    width:320px;
    border: 1px solid #EEE;
    border-radius: 5px;
    background-color: #F1F1F1;
    display:inline-block;
}
form{
    padding: 2px 16px;
    margin-top: 0;
    overflow:hidden;
}
div.form input{
    margin-top: 8px;
    font-size: 15px;
    padding: 6px 5px;
    border-radius:5px;
    border: 1px solid #CCC;
    color:#A8A8A8;
}
div.form input:focus{
    outline: 0;
    color:#333;
    border-color: #56B4EF;
    -webkit-box-shadow: inset 0 1px 3px rgba(0,0,0,.05),0 0 8px rgba(82,168,236,.6);
    -moz-box-shadow: inset 0 1px 3px rgba(0,0,0,.05),0 0 8px rgba(82,168,236,.6);
    box-shadow: inset 0 1px 3px rgba(0,0,0,.05),0 0 8px rgba(82,168,236,.6);
}
#login{
    height: 110px;
    margin: 5px 22px 10px 10px;
}
#sigin{
    height:225px;
    margin:2px 22px 5px 10px;
}
#sigin h2{
    font-size:20px;
    line-height:30px;
    border-bottom: 1px solid #E2E2E2;
    padding-left: 17px;
    margin: 0;
    height: 30px;
}
#username,#username2,#password2,#email2{
    width:280px;
}
#password{
    width: 198px;
    position:relative;
}
span{
    background-position: left center;
    background-repeat: no-repeat;
    font-size: 13px;
    padding-left: 13px;
    display:none;
}
span.accept{
    background-image: url("image/accept.png");
    color: green;
    display:inline-block;
}
span.error{
    background-image: url("image/error.png");
    color: red;
    display:inline-block;
}
#submit1{
    padding: 4px 14px;
    font-size: 15px;
    margin: 0;
    background: #14A7DD;
    border-radius: 5px;
    border: 1px solid #CCC;
    color: white;
    background-image: -khtml-gradient(linear,left top,left bottom,from(#2DADDC),to(#0271BF));
    background-image: -moz-linear-gradient(#2DADDC,#0271BF);
    background-image: -ms-linear-gradient(#2DADDC,#0271BF);
    background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0%,#2DADDC),color-stop(100%,#0271BF));
    background-image: -webkit-linear-gradient(#2DADDC,#0271BF);
    background-image: -o-linear-gradient(#2DADDC,#0271BF);
    background-image: linear-gradient(#2DADDC,#0271BF);
    height: 30px;
    width: 64px;
}
#submit2{
    padding: 5px 16px;
    font-size: 15px;
    margin: 12px 50px 0 0;
    border: 1px solid #CCC;
    float: right;
    border-radius: 5px;
    background:#FEC835;
    background-image: -khtml-gradient(linear,left top,left bottom,from(#FFEC46),to(#FA2));
    background-image: -moz-linear-gradient(#FFEC46,#FA2);
    background-image: -ms-linear-gradient(#FFEC46,#FA2);
    background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0%,#FFEC46),color-stop(100%,#FA2));
    background-image: -webkit-linear-gradient(#FFEC46,#FA2);
    background-image: -o-linear-gradient(#FFEC46,#FA2);
    background-image: linear-gradient(#FFEC46,#FA2);
    height: 40px;
    width:147px;
    color:black;
}
#submit1:hover, #submit2:hover{
    cursor:pointer;
}
article,aside,dialog,footer,header,section,footer,nav,menu{display:block}
</style>
<!–[if lt IE9]> 
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]–>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
$(function(){
$("section").height(document.documentElement.clientHeight-43);
$(window).resize(function(){
    if(document.documentElement.clientHeight > 600){
        $("section").height(document.documentElement.clientHeight-43);
    }
});

//表单默认文字颜色
$("input:not(input:submit)").focus(function(){
    $(this).css("color","#333");
    var txtValue = $(this).val();
    if(txtValue == this.defaultValue){
        $($(this).val(""));
    }
});
$("input:not(input:submit)").blur(function(){
    var inputValue = $(this).val();
    if(inputValue == ""){
        $(this).val(this.defaultValue);
        $(this).css("color","#A8A8A8");
    } else{
        $(this).css("color","#333");
    }
}); 

//表单验证
$("#username2,#email2,#password2").focus(function(){
    if(!$(this).is(":animated") && $(this).width() >230){
        $(this).animate({width:"-=50"},400);
    }
});

$("#username2").blur(function(){
    var inputValue = $(this).val();
    if(inputValue.length < 6){
        $("#username2").next().removeClass();
        $("#username2").next().addClass("error");
        $("#username2").next().text("太短");
        return ;        
    }
    if(!/^[a-zA-Z]\w*/.test(inputValue)){
        $("#username2").next().removeClass();
        $("#username2").next().addClass("error");
        $("#username2").next().text("无效");
        return;
    }
    $.post("verify.php",{usernameValue:inputValue},function(html){
        var result = html.result;
        if(result == "succeed"){
            $("#username2").next().removeClass();
            $("#username2").next().addClass("accept");
            $("#username2").next().text("可用");
        } else{
            $("#username2").next().removeClass();
            $("#username2").next().addClass("error");
            $("#username2").next().text("已用");
        } 
    },"json"); 
});

$("#email2").blur(function(){
    var inputValue = $(this).val();
    if(!/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/.test(inputValue)){
        $("#email2").next().removeClass();
        $("#email2").next().addClass("error");
        $("#email2").next().text("无效");
        return;
    }
    $.post("verify.php",{emailValue:inputValue},function(html){
        var result = html.result;
        if(result == "succeed"){
            $("#email2").next().removeClass();
            $("#email2").next().addClass("accept");
            $("#email2").next().text("可用");
        } else {
            $("#email2").next().removeClass();
            $("#email2").next().addClass("error");
            $("#email2").next().text("已用");
        } 
    },"json");
    
});

$("#password2").blur(function(){
    var inputValue = $(this).val();
    
    if(inputValue.length > 6){
        $("#password2").next().removeClass();
        $("#password2").next().addClass("accept");
        $("#password2").next().text("可用");
        
    } else{
        $("#password2").next().removeClass();
        $("#password2").next().addClass("error");
        $("#password2").next().text("太短");
    }
});

//阻止注册表单提交
$("#submit2").click(function(){
    if($("#sigin form span.error").length != 0){
        return false;
    }
});
//摇头函数
function shake(inputType){
    if ($("#"+inputType).is(":animated")){
      return ;
    }
    $("#"+inputType).animate({left:"60px"},70);
    $("#"+inputType).css("border","2px solid red");
    $("#"+inputType).animate({left:"-60px"},140);
    $("#"+inputType).animate({left:"0px"},70);
    $("#"+inputType).animate({left:"60px"},50);
    $("#"+inputType).animate({left:"-60px"},100);
    $("#"+inputType).animate({left:"0px"},50);
}
//登录处理
$("#submit1").click(function(){ 
    var user = $("#username").val();
    var pass = $("#password").val();
    if(user.length <6 || pass.length<6){
        shake("password");
        return false;
    }
    $.post("verify.php",{user:user,pass:pass},function(html){  
        var result = html.result;
        if(result == "succeed"){
            location='web.php';
        } else{
            var pass = $("#password").val("");
            shake("password");
        }
    },"json");
    return false;
});

});
</script>
</head>
<body>
<header>
    <div id="logo"><img src="image/logo2.png" alt="" /></div>
</header>

<section>
    <article>
        <div id="pic"></div>
        <div id="login" class="form">
        	<form action="" method="post">
                <input type="text" name="username" id="username" value="电子邮件或者用户名" />
                <input type="password" name="password" id="password" placeholder="密码" />
                <input type="submit" value="登录" id="submit1" />
            </form>
        </div>
        <div id="sigin" class="form">
        <h2>新用户？注册一下吧</h2>
        	<form action="register/register.php" method="post">
                <input type="text" name="username2" id="username2" value="您的用户名【至少6位】" />
                <span>可用</span>
                <input type="password" name="password2" id="password2" placeholder="密码【至少6位】" />
                <span>可用</span>
                <input type="email" name="email2" id="email2" value="电子邮件地址" />
                <span>可用</span>
                <input type="submit" value="注册Kindle阅读" id="submit2" />
            </form>
        </div>
    </article>
</section>	

</body>
</html>