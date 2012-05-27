<?php
//加载RSS解析文件
require 'F:\Server\htdocs\simplepie\simplepie.inc';
set_time_limit(300);

//数据库连接
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
//建立的数据连接说明
//$insertArticles 是用来获取源中的文章并插入到Articles数据表中的
//$updateMd5 是用来更新指定id的博客md5值
//$selectRSS 是用来查询所有RSS表中的数据
//$insertRSS 是用来插入一条RSS记录到RSS数据表中的
//$selectOneRSS  是用来查询指定的一条RSS纪录


//读取单个源的函数
function readRSS($rssId,$updateMd5,$feed){
    $db = DBCxn::get();
    $sql = 'INSERT INTO articles(rssId,title,pubDate,author,titleUrl,content) VALUES(:rssId,:title,:pubDate,:author,:titleUrl,:content)';
    $insertArticles = $db->prepare($sql);
    //绑定参数
    $insertArticles->bindParam(':rssId',$rssId);
    $insertArticles->bindParam(':title',$title);
    $insertArticles->bindParam(':pubDate',$pubDate);
    $insertArticles->bindParam(':author',$author);
    $insertArticles->bindParam(':titleUrl',$titleUrl);
    $insertArticles->bindParam(':content',$content,PDO::PARAM_LOB);//作为流对象传入
    
    $temp = 0;
    //记录条目总数
    $items_length = count($feed->get_items());
    foreach($feed->get_items() as $item){
        $titleUrl = $item->get_link();
        //记录第一条URL
        $temp++;
        if($temp == 1){
            $newUpdateMd5 = md5($titleUrl);
        }            
        //检测确保不会重复收录,并且更新MD5
        if($updateMd5 == md5($titleUrl)){
            $updateMd5 = $db->prepare('Update rss SET updateMd5=? WHERE id=?');
            $updateMd5->execute(array($newUpdateMd5, $rssId));
            break;
        }
        
        //读到最后一条记录也要更新MD5
        if($temp == $items_length){
            $updateMd5 = $db->prepare('Update rss SET updateMd5=? WHERE id=?');
            $updateMd5->execute(array($newUpdateMd5, $rssId));
        }
        
        $title = $item->get_title();//文章标题
        
        //将时间全部转换为PRC时间
        $pubDateGTM = $item->get_date();//发表日期
        $pubDate = date('Y-m-d H:i:s',strtotime($pubDateGTM.' + 8 hours'));
        
        //文章作者
        if ($author = $item->get_author())
        {
            if($author->get_name()){
                $author = $author->get_name();
            } else{
                $author = $author->get_email();
            }
        } else{
            $author = "";
        }
        
        //读取文章正文
        if( $item->get_content() == '' ){
            continue;
        } else{
            $content = $item->get_content();
        }
        //执行数据库插入操作
        $insertArticles->execute();
    }
    echo "编号为".$rssId."的RSS已经更新完成！";
}    
    


//更新源
function updateRSS(){
    $db = DBCxn::get();   
    $selectRSS = $db->query("SELECT id, rssLink, updateMd5 FROM rss");
    $selectRSS->setFetchMode(PDO::FETCH_ASSOC);
    $selectRSS = $selectRSS->fetchAll();
    
    //构建分析
    $feed = new SimplePie();
    $feed->enable_order_by_date(false);
    $feed->enable_cache(true);
    $feed->set_useragent('Mozilla/4.0 '.SIMPLEPIE_USERAGENT);
    $feed->set_cache_location($_SERVER['DOCUMENT_ROOT'] . '/cache');
    
    //拿出每个RSS调用分析函数
    foreach($selectRSS as $rows){
        $rssId = $rows['id'];//博客ID
        $updateMd5 = $rows['updateMd5'];//最后更新记录的md5值
        $feed->set_feed_url($rows['rssLink']); //feed地址做参数进行解析操作
        $feed->set_timeout(30);
        $feed->init();
        //如果feed出错，执行下一个
        if($feed->error()){
            continue;
        }
        readRSS($rssId,$updateMd5,$feed);
    }

    return true;
}

//执行更新操作

if(updateRSS()){
    echo "更新完成";
}else{
    echo "更新失败";
}



?>