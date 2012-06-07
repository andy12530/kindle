<?php
session_start();
if(isset($_SESSION['user'])){
    $user = $_SESSION['user'];
}else{
    header('Location: index.php');
}
require 'F:\Server\htdocs\simplepie\simplepie.inc';

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

//初使化订阅
function initRss($rssLink, $user){
    $db = DBCxn::get();
       
    //构建对象
    $feed = new SimplePie();
    $feed->set_feed_url($rssLink); //feed地址做参数进行解析操作
    $feed->set_timeout(30);
    $feed->enable_cache(false);
    $feed->init();
    if($feed->error()){
        //feed地址错误
        //echo $feed->error();
        return "error";
    }
    $blogName = $feed->get_title();
    $blogLink = $feed->get_link();
        
    $selectOneRSS = $db->prepare("SELECT id FROM rss WHERE blogLink = ?");
    $selectOneRSS->execute(array($blogLink));
    $blogLinkId = $selectOneRSS->fetchColumn();//此RSS在数据库表中id


    
    //检查是否存在，如果不存在添加此feed
    if( $blogLinkId > 0 ){//echo "此订阅源已经存在！";
        //检查用户是否已经订阅
        $checkSub = $db->prepare("SELECT count(*) FROM readinfo WHERE userName = ? AND rssId = ?");
        $checkSub->execute(array($user, $blogLinkId));
        $boolCheckSub = $checkSub->fetchColumn();
        if($boolCheckSub != 1){//用户没有订阅
            $insertReadInfo = $db->prepare("INSERT INTO readinfo(userName,rssId) VALUES(:user,:blogLinkId)");
            $insertReadInfo->bindParam(':user',$user);
            $insertReadInfo->bindParam(':blogLinkId',$blogLinkId);
            $insertReadInfo->execute();
            //"此源已经存在，你之前尚未订阅，现在已经订阅";
            //return "succeed1";
            return "rss-".$blogLinkId;
        } else{
            //"此源已经存在，你之前已经订阅";
            return "succeed2";
        }        
    } else{
        $sql = "INSERT INTO rss(blogName,blogLink,rssLink,updateMd5) VALUES(:blogName,:blogLink,:rssLink,:updateMd5)";
        $insertRSS = $db->prepare($sql);
        $insertRSS->bindParam(':blogName',$blogName);
        $insertRSS->bindParam(':blogLink',$blogLink);
        $insertRSS->bindParam(':rssLink',$rssLink);
        $insertRSS->bindParam(':updateMd5',$updateMd5);
        $updateMd5 = md5("123456");
        $insertRSS->execute();
        
        //查询该源的ID
        $selectOneRSS = $db->prepare("SELECT id FROM rss WHERE blogLink = ?");
        $selectOneRSS->execute(array($blogLink));
        $blogLinkId = $selectOneRSS->fetchColumn();
        //订阅此源
        //$db->exec("INSERT INTO readinfo(userName,rssId) VALUES ($user, $blogLinkId)");
        $insertReadInfo = $db->prepare("INSERT INTO readinfo(userName,rssId) VALUES(:user,:blogLinkId)");
        $insertReadInfo->bindParam(':user',$user);
        $insertReadInfo->bindParam(':blogLinkId',$blogLinkId);
        $insertReadInfo->execute();
       //"此源不存在，现在已经订阅";
        return "rss-".$blogLinkId;
    }  

}

//执行订阅初使化
if(isset($_POST['feed'])){
    $rssLink = strtolower(trim($_POST['feed']));
    $data['result'] = initRss($rssLink, $user);
    echo json_encode($data);
}



//以下是所有文章列表
$selectReadInfo = $db->prepare("SELECT DISTINCT readInfo FROM readinfo WHERE userName = ?");
$selectReadInfo->execute(array($user));
$readInfo = unserialize($selectReadInfo->fetchColumn());

@$loadType = strval($_POST['loadType']);
if($loadType == 'titleList'){
    $page = intval($_POST['page']);
    $start = ($page+1) * 20;
    $sql_1 = "SELECT * FROM articles WHERE rssId IN(SELECT DISTINCT rssId FROM readinfo WHERE userName=?) ORDER BY pubDate DESC, id LIMIT $start, 20";
    //语句有问题，传送start值会不显示数据
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
} 

//加载指定的源的所有文章列表
if(isset($_POST['curRssId'])){
    $curRssId= $_POST['curRssId'];
    $curRssId= substr($curRssId,4);
    $sql_2 = "SELECT * FROM articles WHERE rssId= ? ORDER BY pubDate DESC, id";
    $st2 = $db->prepare($sql_2);
    $st2->execute(array($curRssId));
    //显示文章列表
    foreach($st2->fetchAll() as $row ){
        if(isset($readInfo[$row['id']])){//查询是否已读
            echo '<li><a href="javaScript:" id="all-'.$row['id'].'" class="readed" ><h3>'.$row['title'].'</h3><p>作者：'.$row['author'].'</p><span></span></a></li>';
        } else{
            echo '<li><a href="javaScript:" id="all-'.$row['id'].'" class="unreaded" ><h3>'.$row['title'].'</h3><p>作者：'.$row['author'].'</p></a></li>';
        }
    }
    
}

//载入文章内容
if(isset($_POST['id'])){
    $id = $_POST['id'];
    $id = intval(substr($id,4));
    $readed = strval($_POST['readed']);//文章已读状态
    $st2 = $db->query("SELECT * FROM articles where id = $id");
    foreach($st2->fetchAll() as $row ){
        $data =array(
            'content' => '<div class="entry">'.$row['content'].'</div>',
            'title' => $row['title'],
            'pubDate' => $row['pubDate'],
            'author' => $row['author'],
            'titleUrl' => $row['titleUrl']
        );
        $rssId = $row['rssId'];
        $id = $row['id'];
    }
    //更新文章状态
    if($readed != "readed"){
        $readInfo[$id] = "readed";
        $newReadInfo = serialize($readInfo);
        $st2 = $db->prepare('UPDATE readinfo SET readInfo = ? WHERE userName =?');
        $st2->execute(array($newReadInfo, $user));
    }
    echo json_encode($data);
}

//退订源操作
if(isset($_POST['rssId'])){
    $rssId = $_POST['rssId'];
    $rssId = substr($rssId,4);
    $cancelRSS = $db->prepare('DELETE FROM readinfo WHERE userName=? AND rssId=?');
    if( $cancelRSS->execute(array($user, $rssId)) ){
        $data['result'] = "succeed";
    } else{
        $data['result'] = "error";
    }
    echo json_encode($data);
}

//标记所有文章已读
if(isset($_POST['allId'])){
    $allId = $_POST['allId'];
    $allId = explode("all-", $allId); 
    $st2 = $db->prepare('UPDATE readinfo SET readInfo = ? WHERE userName =?');
    foreach($allId as $Id){
        $readInfo[$Id] = "readed";
    }
    $newReadInfo = serialize($readInfo);
    if( $st2->execute(array($newReadInfo, $user)) ){
        $data['result'] = "succeed";
    } else{
        $data['result'] = "error";
    }
    echo json_encode($data);
}

?>