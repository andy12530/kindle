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
//处理干净字符串
function safe($str){
   return strtolower(trim($str));
}
//存放返回结果的数组
$data = array();
//验证用户名是否存在
if(isset($_POST['usernameValue'])){
    $userName = safe($_POST['usernameValue']);
    $st = $db->prepare("SELECT COUNT(*) FROM users WHERE userName = ?");
    $st->execute(array($userName));
    $st_Num = $st->fetchColumn();
    if($st_Num == 0){
        $data = array("result" => "succeed");
    } else{
        $data = array("result" => "false");
    }
    echo json_encode($data);
    unset($data);
}
//验证邮箱是否存在
if(isset($_POST['emailValue'])){
    $email = safe($_POST['emailValue']);
    $st = $db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $st->execute(array($email));
    $st_Num = $st->fetchColumn();
    if($st_Num == 0){
        $data = array("result" => "succeed");
    } else{
        $data = array("result" => "false");
    }

    echo json_encode($data);
    unset($data);
}
//处理用户登录信息
if(isset($_POST['user']) && isset($_POST['pass'])){
    $userName = safe($_POST['user']);
    $pwd = safe($_POST['pass']);
    $st = $db->prepare("SELECT COUNT(access_token) result_sum, access_token FROM users WHERE userName=? AND pwd =?");
    $st->execute(array($userName,sha1($pwd) ));
    $st_result = $st->fetchAll();
    if($st_result[0]['result_sum'] == 1){
        $data = array("result" => "succeed");
        session_start();
        $_SESSION['user'] = $userName;
        //在这里读取用户GR授权信息
        $_SESSION['access_token'] = $st_result[0]['access_token'];
    } else{
        $data = array("result" => "false");
    }
    echo json_encode($data);
    unset($data);
}

?>