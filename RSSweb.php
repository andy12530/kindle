<!DOCTYPE HTML>
<html lang="en-US">
<head>
<meta charset="UTF-8">
<title>Web 阅读器</title>
<style type="text/css">
body{
    background:white url(https://www.google.com/reader/ui/359886343-shared-bkg.png) repeat-y center top;
    font-family:Helvetica, Arial, sans-serif;
    width: 700px;
    margin: 0 auto;
}
p{
    line-height:1.5em;
    word-wrap: break-word;
}
a{
    color:#15c;
}
.title p{
    color:#777;
    font-size:90%;

}
.title h3 a{
    background:url(http://www.amzbook.com/wp-includes/images/outlink.png) no-repeat right center;
    padding-right:15px;
    color: #4F7FC9;
    border-bottom: 1px solid #CCC;
    text-decoration: none;
}

</style>
</head>
<body>
<?php
//error_reporting(0);
require 'F:\Server\htdocs\magpierss\rss_fetch.inc';
$feed = "http://pipes.yahoo.com/pipes/pipe.run?_id=0e10b126a7255a3b48d5b3b3091016d1&_render=rss";
$rss = fetch_rss($feed);
$header= $rss->channel;

echo '<div id="header"><h1>'.$header['title'].'</h1></div>';
foreach($rss->items as $item){
?>
    <div class="title">
        <h3><a href="<?php echo $item['link']  ?>"><?php echo $item['title'] ?></a></h3>
        <p>作者：<?php echo $item['author'] ?> | 时间：<?php echo $item['pubdate'] ?></p>
    </div>
	<div class="content">
        <div class="entry">
			<?php 
            if(isset($item['description'])){
                echo $item['description'];
            } else{
    continue;
}   ?>
		</div>
    </div>
    <hr />
<?php
}
?>
</body>
</html>