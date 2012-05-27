<?php
session_start();
if(isset($_SESSION['user'])){
    $user = $_SESSION['user'];
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
<meta charset="UTF-8">
<title>Web阅读（双栏目）</title>
<style type="text/css">
body{
    font-family:"Hiragino Sans GB", "Helvetica Neue", Helvetica,Sans-serif;
    background-color: #474747;
}
#reader{
    margin:0;
    padding:0;
    border:none;
    outline:none;
    text-decoration: none;
}
#left-pane{
    background:#E7EBEF;
    width:320px;
    position:fixed;
    left:0;
    top:0;
}
#left-pane header{
    padding:19px;
    height:35px;
    background:#C8CFD4;
    background:-webkit-gradient(linear,0% 0%, 0% 100%, from(#DFE3E6), to(#B7C0C7));
    background:-moz-linear-gradient(top, #DFE3E6, #B7C0C7);
    background:linear-gradient(top, #DFE3E6, #B7C0C7);
}
#left-pane header span{
    position: relative;
    left: 30px;
    top: -16px;
}
#left-pane header span a{
    display: inline-block;
    padding: 5px 25px;
    margin-top: 10px;
    border: 1px solid #A7B1B8;
    border-radius: 8px;
    text-decoration: none;
    font-size: 17px;
    color: #656565;
    background: #DCE1E4;
}

#select{
    padding:15px 20px;
    line-height:30px;
    height:30px;
    background:-webkit-gradient(linear, 0% 0%, 0% 100%, from(#F9F9F9), to(#E4E4E4));
    -webkit-user-select: none;
    overflow: hidden;
}
#select span{
    color:#999;
}
#select ul{
    list-style-type:none;
    padding:0;
    margin:0;
    /*这是直接用float:left，不兼容IE7*/
    width: 168px;
    position: relative;
    left: 120px;
    top: -30px;
    font-weight: bold;
}
#select ul li{
    float:left;
    border:1px solid #BEC6CC;
}
#list, #list a, #list a:hover{
    border-radius:50px 0 0 50px;
}
#search{
    border-radius:0 50px 50px 0;
}
#select ul li a{
    display:block;
    padding:0 12px;
    text-decoration:none;
    font-size:15px;
    color: #758694;
}

nav ul{
    list-style-type:none;
    padding:0;
    margin:0;
    display:block;
    background: #F9F9F9;
    height:505px;/*默认高度为此，JS会动态改变此值*/
    overflow:auto;
}
nav ul a{
    text-decoration:none;
    color:#999;
    padding: 2px 40px 2px 15px;
    display: block;
    max-height:70px;
    border-bottom: 1px dotted #AEB7BF;
}
nav.list ul a{
    min-height: 56px;
}
/*已经读过的文章*/
nav a.readed h3{
    font-weight: normal;
}
nav a.readed{
    background:white url("image/read.png") no-repeat 270px 30px;
}
nav ul a.readed:hover{
    background:#F5F5F5 url("image/read.png") no-repeat 270px 30px;
}
nav ul a:hover{
    background:#F5F5F5;
}

nav ul a h3{
    font-size:16px;
    padding:0;
    margin:0;
    line-height:22px;
    max-height:44px;
    min-height:40px;
    overflow:hidden;
    margin-bottom: 8px;
}

/*订阅列表*/
nav.rss{
    display:none;
}
nav.rss ul a{
    display: inline-block;
    width: 240px;
    padding: 2px 0 2px 15px;
}
nav.rss ul a h3{
    min-height:36px;
    margin-bottom:0;
    line-height:40px;
}
/*订阅列表退订按钮*/
nav.rss ul li span{
    display: inline-block;
    padding: 3px 5px;
    border: 1px solid white;
    color: #333;
    border-radius: 8px;
    font-size: 13px;
    cursor:pointer;
    margin-left: 3px;
    background-color: #CCD3D8;
}
nav.rss ul li span:hover{
    background-color: #E33100;
    color: white;
}
/*订阅列表*/


nav ul li p{
    height:20px;
    margin: 0;
    line-height:20px;
    font-size: 13px;
}
#left-pane li.cur a{
    background-color: #DDD!important;
    -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.15) inset!important;
    color: #51A436;
}


div#delete{
    position: absolute;
    left: 0;
    top: 0;
    background-color: #666;
    width: 1366px;
    height: 643px;
    z-index: 10000;
    opacity: 0.6;
    filter:alpha(opacity=60);
    display:none;
}
div#confirm,div#loading{
    position: absolute;
    left: 400px;
    top: 230px;
    width: 390px;
    height: 120px;
    background-color: white;
    z-index: 10001;
    padding: 5px 5px 5px 20px;
    border-radius:15px;
    display:none;
}
div#loading img{
    margin-left: 120px;
}
div#loading p{
    text-align: center;
}
div#confirm span{
    color: #C66423;
}
div#confirm button{
    padding: 6px 17px 8px;
    background: #14A7DD;
    border-radius: 5px;
    border: 1px solid #CCC;
    font-weight: bold;
    color: white;
    margin: 10px 50px 5px 50px;
    cursor: pointer;
}
div#confirm button:hover{
    background-color: #D24231;
}
/*特定博客的文章列表*/
nav.rssList{
    display:none;
}
div#topControl{
    height: 50px;
    overflow: hidden;
    border-bottom: 1px solid #CCC;
    padding-top: 18px;
    padding-left: 20px;
    font-size:15px;
}
#topControl a{
    float: left;
    text-decoration: none;
    border:1px solid #BEC6CC;
    padding: 7px 12px;
}
#topControl a:hover{
    color:
}
#allReaded{
    border-radius: 0 50px 50px 0;
}
#back{
    border-radius: 50px 0 0 50px; 
    background-color: #0090BC;
    color: white;
    border: 1px solid #A7B1B8;
}

/*中间列文章列表*/




#right-pane{
    position:fixed;
    top:5px;
    left:321px;
    background: #F3F2EE;
    width:850px;
    border-radius:6px;
    height:100%;
}
#right-pane header{
    color:#999;
    border-bottom:2px solid #BFBFBF;
}
#right-pane header h1{
    line-height:50px;
    font-size:24px;
    padding:0 50px;
    margin:0;
    text-align: center;
    text-shadow:0 1px 0 white;
    color: #222;
}
#right-pane header h3 {
    margin: 0;
    font-size: 15px;
    text-align: center;
}
#right-pane header h3 a{
    color:#038543;
}
section{
    padding:30px 50px 30px ;
    overflow-y: auto;
    overflow-x: hidden;
    font-size: 15px;
    color: #666;
    text-shadow: 0 1px 0 white;
    height:508px;/*默认高度为此，JS会动态改变此值*/
}
section a{
    color:#038543;
    text-decoration:none;
}
section div.entry{
    border:1px solid #C5CACE;
    border-radius:6px;
    background:#FFF;
    color:#333;
    padding:20px 45px 30px 45px;
    word-wrap: break-word;

}
#addRSS{
    height: 70px;
    padding-left: 15px;
    padding-top:20px;
    border-bottom: 1px solid #AEB7BF;
    background: #1E9DCC;
    color: white;
}
#addRSS input{
    color:#A8A8A8;
    width: 210px;
    height: 22px;
    padding: 1px 1px 1px 3px;
    font-size: 14px;
    border-radius: 5px;
    border: 1px solid #CCC;
}
#addRSS input:focus{
    outline:0;
    border-color:#56B4EF;
}
#addRSS button{
    margin-left: 5px;
    height: 26px;
    padding: 2px 8px;
    border: 1px solid #999;
    color: white;
    background-color: #666;
    border-radius: 5px;
    cursor:pointer;
}
#addRSS button:hover{
    background-color: #444;
}
#addRSS p{
    margin-right:10px;
}
#up{
    position: fixed;
    left: 1107px;
    bottom: 10px;
    cursor:pointer;
    display:none;
}
article,aside,dialog,footer,header,section,footer,nav,menu{display:block}
</style>
<!–[if lt IE9]> 
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]–>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
$(function(){ 
//边框大小
var clientHeight = document.documentElement.clientHeight;
$("nav ul").height(clientHeight - 133);
$("section").height(clientHeight-$("#right-pane header").height()-117);
$(window).resize(function(){
    var clientHeight = document.documentElement.clientHeight;
    $("nav ul").height(clientHeight - 133);
    $("section").height(clientHeight-$("#right-pane header").height()-67);
});
//遮罩的大小
$("div#delete").height(clientHeight);
$("div#delete").width(document.documentElement.clientWidth);
//如果用户登录后没有条目，删除#showMore标签
if($("nav.list ul li").length === 1){
    $("#showMore").remove();
}

var pageI = 0;
var temp = 0;//解决IE加载同步的问题
$("nav.list ul").scroll(function(){
    if(($("nav.list ul li").length-1)%20 !==0){
        $("#showMore").remove();
        return;
    }
    if($("#list").attr("class") == "cur"){
        var scrollHeight = $("#showMore").offset().top - $("nav ul").height();
        if(scrollHeight < 66 && pageI == temp){//达到缓冲值，准备加载新数据 
            temp++;
            setTimeout(function(){
                $.post("loadInfo.php",{page:pageI,loadType:'titleList'},function(html){
                    $(html).insertBefore("#showMore");  
                    pageI++;
                });
            },500);
        } 
    }  
});

//一键回顶功能
$("section").scroll(function(){
    if($("section").scrollTop() > $("section").height()*2){
        $("#up").show();
    }else{
        $("#up").hide();
    }
});
//点击回顶
$("#up").click(function(){
    $("section").animate({scrollTop:"0"}, 1000);
});

//初使化
$(function(){ 
    $("nav.list ul li").eq(0).click();

});
//绑定键盘事件
$(document).keyup(function(){
    if($("#list").attr("class") == "cur"){
        if(event.keyCode == 37){
            $("nav ul li.cur").prev().trigger("click");
            //同时让滚动条动起来
            var nowHeight = $("nav ul").scrollTop();
            $("nav ul").scrollTop(nowHeight-75);
        }
        if(event.keyCode == 39){
            $("nav ul li.cur").next().trigger("click");
            var nowHeight = $("nav ul").scrollTop();
            $("nav ul").scrollTop(nowHeight+75);
        }
    }
});

$("#list").click(function(){
    $("nav.rss").fadeOut("normal");
    $("#rss").removeClass();
    $("nav.list").fadeIn("normal");
    $("#list").addClass("cur");
    $("nav.list ul li.cur").click();
});

$("#rss").click(function(){
    $("nav.list").fadeOut("normal");
    $("#list").removeClass();
    $("nav.rss").fadeIn("normal");
    $("#rss").addClass("cur");
    
});


$("#search").click(function(){
    alert("抱歉，此项功能暂未开放，敬请期待……");
});

//这里使用live事件
$("nav.list li, nav.rssList ul li:not(#top)").live("click",function(){
    $(this).parent().children().removeClass();
    $(this).addClass("cur");
    var idValue = $(this).children("a").attr("id");
    var classValue = $(this).children("a").attr("class");
    //从数据库获取相关信息，然后改变div.entry的值。
    $.post("loadInfo.php",{id:idValue,readed:classValue},function(html){
        var content = html.content;
        var author = html.author;
        var title = html.title;
        var pubDate = html.pubDate;
        var titleUrl = html.titleUrl;
        $("div.entry").replaceWith(content);
        $("header h1").text(title);
        $("header h3").html("作者："+author+"  &nbsp;&nbsp;发表时间："+pubDate+"&nbsp;&nbsp;<a target=\"_blank\" href=\""+titleUrl+"\">查看原文</a>");
        //回顶
        $("section").scrollTop(0);
        //调整正文高度
        $("section").height(clientHeight-$("#right-pane header").height()-67);
        //添加图片缩放功能
        for(var i=0; i<$("div.entry img").length; i++){
            if($("div.entry img:eq("+i+")").width()>600){
                $("div.entry img:eq("+i+")").width(600);
            }
        }
        
    }, "json");
    //更新前端信息，确保已读
    if( classValue != "readed"){
        $(this).children("a").attr("class","readed");
    }

});
//添加图片缩放功能
// $("section").click(function(){ 
    // for(var i=0; i<$("section img").length; i++){
        // if($("section img:eq("+i+")").width()>600){
            // $("section img:eq("+i+")").width(600);
        // }
    // }
// });
//
$("#addRSS input").focus(function(){
    var txtValue = $(this).val();
    if(txtValue == this.defaultValue){
        $($(this).val(""));
        $(this).css("color","#333");
    }
});
$("#addRSS input").blur(function(){
    var inputValue = $(this).val();
    if(inputValue == ""){
        $(this).val(this.defaultValue);
        $(this).css("color","#A8A8A8");
    } else{
        $(this).css("color","#333");
    }
});

//添加订阅源
$("#addRSS button").bind("click",function(){
    var feed =$("#addRSS input").val();
    if(feed.length < 6){
        alert("订阅源地址不合法");
        return false;
    }
    $("div#loading p").text("正在检测RSS源……");
    $("div#delete").css("display","block");
    $("div#loading").css("display","block");
    $.post("loadInfo.php",{feed:feed},function(html){
        var result = html.result;
        if(result == "error" ){
            $("div#loading p").css("color","red");
            $("div#loading p").text("订阅源有误，请输入合法的RSS地址");
            setTimeout(function(){
                $("div#delete").fadeOut(900);
                $("div#loading").fadeOut(900);
            },2000);
        } else if(result == "succeed2"){
            $("div#loading p").css("color","red");
            $("div#loading p").text("你之前已经订阅过了！勿重复订阅");
            setTimeout(function(){
                $("div#delete").fadeOut(900);
                $("div#loading").fadeOut(900);
            },2000);
        } else{
            $("div#loading p").text("订阅成功！（信息将会在今天晚些时间推送，请勿再次提交订阅！）");
            $("#addRSS").after("<li><a href=\"JavaScript:\" id=\""+result+"\"><h3>新订阅</h3></a><span class=\"cancelSub\">退订</span></li>");
            $("#addRSS").next().css("background-color","#FFFFAF");
            setTimeout(function(){
                $("div#delete").fadeOut(900);
                $("div#loading").fadeOut(900);
            },3000);
            
            //window.location.reload();
        }
    }, "json");
});
//退订源
$("nav.rss ul li span").live("click",function(){
    var rssName = $(this).prev().children().text();
    var rssId = $(this).prev().attr("id");
    $("div#delete").css("display","block");
    $("div#confirm").css("display","block");
    $("div#confirm span").text(rssName);
    $("div#confirm button:eq(0)").attr("id", rssId)
    
});
//点击取消
$("div#confirm button:eq(1)").click(function(){
    $("div#delete").css("display","none");
    $("div#confirm").css("display","none");
});
//确认退订
$("div#confirm button:eq(0)").click(function(){
    var rssId = $(this).attr("id");
    $("div#delete").css("display","none");
    $("div#confirm").css("display","none");    
    $.post("loadInfo.php",{rssId:rssId},function(html){
        var result = html.result;
        if(result == "succeed"){
            $("div#delete").css("display","block");
            $("div#loading").css("display","block");
            $("div#loading p").text("退订成功，你将不会再收到此RSS的推送");
            $("nav.rss ul li a#"+rssId+"").parent().remove();
            setTimeout(function(){
                $("div#delete").fadeOut(900);
                $("div#loading").fadeOut(900);
            },2000);
        } else{
            alert("出错");
        }
    }, "json");
});

//载入同一源下面所有文章
$("nav.rss ul li a").live("click",function(){
    $("nav.rss").slideUp("slow");
    $("nav.rssList").slideDown("slow");
    var rssId = $(this).attr("id");
    $.post("loadInfo.php",{curRssId:rssId},function(html){
        $("nav.rssList ul li").not($("#top")).remove();
        $("#top").after(html);
    });

});

//文章列表上点击返回
$("#back").click(function(){
    $("nav.rss").slideDown("slow");
    $("nav.rssList").slideUp("slow");
});

//点击全部未读切换
$("#allReaded").prev().toggle(function(){
    $("nav.rssList a.readed").fadeOut("slow");
    $(this).text("查看全部");
},function(){
    $("nav.rssList a.readed").fadeIn("slow");
    $(this).text("只看未读");
});
//标记全部已读
$("#allReaded").click(function(){
    var allId = "";
    for(var num=0;num<$("nav.rssList ul li a.unreaded").length; num++){
        allId += $("nav.rssList ul li a.unreaded:eq("+num+")").attr("id"); 
    }
    $.post("loadInfo.php",{allId:allId},function(html){
        var result = html.result;
        if(result == "succeed"){
            for(var num=0;num<$("nav.rssList ul li a.unreaded").length; num++){
                $("nav.rssList ul li a.unreaded").attr("class","readed");
            }
        } else{
            alert("错误");
        }
    }, "json");
    
});

});
</script>
</head>
<body>
<div id="reader">
	<div id="left-pane">
        <!--header区域包括logo以及返回选项-->
        <header>
            <img src="image/logo.png" alt="Kindle阅读" />
            <span><a href="logout.php">退出</a></span>
        </header>
        <!--div#select包括条目，订阅，以及搜索功能-->
        <div id="select">
            <span>Web阅读器</span>
            <ul>
            	<li id="list" class="cur"><a href="javascript:">条目</a></li>
            	<li id="rss"><a href="javascript:">订阅</a></li>
            	<li id="search"><a href="javascript:">搜索</a></li>
            </ul>
        </div>
        <!--nav显示条目，订阅中的内容-->
        <nav class="list">
            <ul>
<?php
class DBCxn{
    public static $dsn = 'mysql:host=localhost;dbname=webrss';
    public static $user = 'root';
    public static $pass = 'root';
    //保存连接的内部变量
    private static $db;
    //不能克隆和技巧化
    final private function __construct(){}
    final private function __clone(){}
    
    public static function get(){
        if(is_null(self::$db)){
            self::$db = new PDO(self::$dsn, self::$user, self::$pass);
        }
        //返回连接
        self::$db->query('set names utf8');
        return self::$db;
    }
}

$db = DBCxn::get();

//根据当前用户查询阅读信息
$selectReadInfo = $db->prepare("SELECT DISTINCT readInfo FROM readinfo WHERE userName = ?");
$selectReadInfo->execute(array($user));
$readInfo = unserialize($selectReadInfo->fetchColumn());

$sql_1 = "SELECT * FROM articles WHERE rssId IN(SELECT DISTINCT rssId FROM readinfo WHERE userName=?) ORDER BY pubDate DESC, id LIMIT 0, 20";
$st1 = $db->prepare($sql_1);
$st1->execute(array($user));
//显示文章列表
foreach($st1->fetchAll() as $row ){
    if(isset($readInfo[$row['id']])){//查询是否已读
        echo '<li><a href="javaScript:" id="all-'.$row['id'].'" class="readed" ><h3>'.$row['title'].'</h3><p>作者：'.$row['author'].'</p><span></span></a></li>';
    } else{
        echo '<li><a href="javaScript:" id="all-'.$row['id'].'" class="unreaded" ><h3>'.$row['title'].'</h3><p>作者：'.$row['author'].'</p></a></li>';
    }
}


//控制博客rss全部显示
$sql_2 ="SELECT id, blogName FROM rss WHERE id IN(SELECT DISTINCT rssId FROM readinfo WHERE userName=?) ORDER BY blogName ASC ";
$selectRss = $db->prepare($sql_2);
$selectRss->execute(array($user));
$resultRss = $selectRss->fetchAll();
?>

                <li id="showMore" ><a href="JavaScript:">
                    <h3>正在加载新内容中……</h3>
                    <p><img src="loadingList.gif" alt="" /></p>
                </a></li>
            </ul>
        </nav>
        <!--nav显示条目，订阅的博客名称-->
        <nav class="rss">
            <ul>
            <li id="addRSS"><input type="text" value="请输入博客或网站RSS地址"  />
            <button>订阅</button>
            <p>例如：http://www.ifanr.com/feed <br/>暂不支持https</p>
            </li>         
            	
<?php
foreach($resultRss as $row){
    echo '<li><a href="JavaScript:" id="rss-'.$row['id'].'" ><h3>'.$row['blogName'].'</h3></a><span class="cancelSub">退订</span></li>';
}
?>
            </ul>
        </nav>
        <nav class="rssList">
            <ul>
                <li id="top">
                    <div id="topControl" >
                        <a href="javaScript:" id="back">返回</a>
                        <a href="javaScript:">查看未读</a>
                    	<a href="javaScript:" id="allReaded">标记全部已读</a>   
                    </div>
                </li>
                
                <!--这里载入当前文章所有条目-->
            </ul>
        </nav>
    </div>
    <div id="delete"></div>
    <div id="loading">
        <img src="loading.gif" alt="" />
        <p>正在检测RSS源……</p>  
    </div>
    <div id="confirm">
        <p>你确定要退订来自<span>博客名称</span>的供稿吗？</p>
        <button value="OK">确定</button><button value="cancel">取消</button>
    </div>
    
    <article id="right-pane">
        <header>
            <h1>从 1998 年至今，互联网行业都有哪些观念上的变化？</h1>
            <h3>作者：keso 发表时间：2012-05-07 19:42:11 <a href="#" target="_blank">查看原文</a></h3>
        </header>
        <section>
            <img id="up" src="image/up.png" alt="" />
        	<div class="entry">
            <a href="javascript:void(0);" onclick="Evernote.doClip({contentId:'answer-content',providerName:'知乎阅读',title:'为什么芬兰，挪威，罗马尼亚等原轴心国在现在基本不会被提到他们原来的战犯史？',url:'http://www.zhihu.com/question/20109279/answer/14016024'}); return false;"> Evernote &nbsp;</a>
			</div>
        </section>
    </article>
</div>	
</body>
</html>
<?php
} else{
    header('Location: index.php');
}
?>