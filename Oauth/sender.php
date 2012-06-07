<?php
require_once 'src/apiClient.php';
require_once 'src/contrib/apiPlusService.php';
include("..\\register\mailMobi.php");

set_time_limit(300);
session_start();
//检查是否登录
if(isset($_SESSION['user'])){
    $user = $_SESSION['user'];
}else{
    header('Location: ..\index.php');
    exit;
}
//构建请求
$client = new apiClient();

//检查用户GR是否已经有授权码
if (isset($_SESSION['access_token']) && strlen($_SESSION['access_token']) > 200 ) {
  $client->setAccessToken($_SESSION['access_token']);
} else{
    header('Location: oauth.php');
}

$html = <<<HTML
<!DOCTYPE HTML>
<html lang="en-US">
<head>
<meta charset="UTF-8">
<title>今日Google Reader</title>
<style>
h2, p.info{
    text-align:center;
    font-weight:bolder;
}
strong{
    font-weight:bolder;
}
img{
    display:none;
}
ul{
  font-style: italic;
}
</style>
</head>
<body>
HTML;
if ($client->getAccessToken()) {
  $req = new apiHttpRequest("http://www.google.com/reader/api/0/stream/contents/?xt=user/-/state/com.google/read&n=50");
  $val = $client->getIo()->authenticatedRequest($req);
  $response = json_decode($val->getResponseBody(),true);
  foreach($response['items'] as $item){
    $html .= '<h2>'.$item['title'].'</h2>'."\n";
    $html .= '<p class="info">作者：'.@$item['author'].'  发表时间：'.date('Y-m-d H:i:s',$item['published']+8*3600).' </p>'."\n";
    if(isset( $item['summary']['content'] )){
      $content = $item['summary']['content'];
    } else{
      @$content = $item['content']['content'];
    }
    $html .= '<div class="entry">'.$content.'</div><mbp:pagebreak />'."\n";
  }
}
$html .= <<<HTML
</body>
</html>
HTML;


$html = chr(0xEF).chr(0xBB).chr(0xBF).$html;
//生成临时文件
$file = '..\tmp\\'.$user.'.html';
$fh = fopen($file, 'wb');
fputs($fh, $html);
fclose($fh);

echo "文件已经生成";

$title = $user.'.html';
$mobiName = "GReader".date('md').$user.".mobi";
if(shell_exec('kindlegen ..\tmp\\'.$title.' -o '.$mobiName)){
  $_SESSION['mobi'] = true;
}

echo "电子书制作完成";

//发送邮件
// if($_SESSION['mobi'] == true){
  // $to ="andy12530@gmail.com";
  // $subject = "您有新的文章推送";
  // $body = "mobi文件";
  // $path = '..\tmp\\'.$mobiName;
  // $attName = $mobiName;
  // if (postmail_attr($to,$subject,$body, $path, $attName) ){
    // echo "发送成功";
  // }
// }
?>
