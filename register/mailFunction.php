<?php
header('Content-Type: text/html; charset=utf-8');
include("class.phpmailer.php");
include("class.smtp.php"); 
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


function postmail_jiucool_com($to,$subject = "",$body = ""){
    //$to 表示收件人地址 $subject 表示邮件标题 $body表示邮件正文
    //error_reporting(E_ALL);
    error_reporting(E_STRICT);
    date_default_timezone_set("Asia/Shanghai");//设定时区东八区
    $mail             = new PHPMailer(); //new一个PHPMailer对象出来
    $body             = eregi_replace("[\]",'',$body); //对邮件内容进行必要的过滤
    $mail->CharSet ="UTF-8";//设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP(); // 设定使用SMTP服务
    $mail->SMTPDebug  = 1;                     // 启用SMTP调试功能
                                           // 1 = errors and messages
                                           // 2 = messages only
    $mail->SMTPAuth   = true;                  // 启用 SMTP 验证功能
    $mail->SMTPSecure = "http";                 // 安全协议
    $mail->Host       = "smtp.exmail.qq.com";      // SMTP 服务器
    $mail->Port       = 25;                   // SMTP服务器的端口号
    $mail->Username   = "no-reply@amzbook.com";  // SMTP服务器用户名
    $mail->Password   = "zhbxtrgw123";            // SMTP服务器密码
    $mail->SetFrom('no-reply@amzbook.com', 'Kindle阅读');
    $mail->AddReplyTo('no-reply@amzbook.com', 'Kindle阅读');
    $mail->Subject    = $subject;
    $mail->AltBody    = "To view the message, please use an HTML compatible email viewer! - From www.jiucool.com"; // optional, comment out and test
    $mail->MsgHTML($body);
    $address = $to;
    $mail->AddAddress($address, "收件人名称");
    //$mail->AddAttachment("images/phpmailer.gif");      // attachment 
    //$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
    if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        return true;
    }
}


//发送邮件前的数据库更新
function sendMail($user,$email,$pass){
    $db = DBCxn::get();

    $str = "ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghigklmnpqrstuvwxyz123456789";
    $verify_string = '';
    for($i=0; $i<32; $i++){
        $verify_string .= $str[mt_rand(0,58)];
    }
    //在这里应该验证用户名是否存在
    $st = $db->prepare("INSERT INTO users (userName,pwd,email,registerOn,verifed,verifyStr)
     VALUES (?,?,?,NOW(),0,?)");
    $st->execute(array($user,$pass,$email,$verify_string));
    //这里应该发送邮件，并且包含字符串
    $body = '<!DOCTYPE HTML>
    <style type="text/css">
    #letter{width:600px;}#header{height:82px;}#header span{font-size:22px;color:white;font-weight:900;}#top{top:10px;left:100px;}#content{border:1px solid #CCC;}#entry,#footer{border-radius:5px;padding:15px 30px 10px 30px;}p{word-wrap:break-word;}p a{color:red;font-weight:bold;}
    </style>
    <div id="letter">
        <div id="header">
            <img id="top" src="top.png" alt="" />
        </div>
        <div id="content">
            <div id="entry">
            <h3>尊敬的用户：</h3>
            <p>您好，欢迎来到kindle阅读在线版，这是一个简单易用的web阅读平台，在这里你会轻松享受阅读的乐趣！</p>
            <p>请点击<a href="">这里</a>的链接完成注册</p>
            <p>如果上面链接无法点击，请复制下面的链接到你的浏览器的地址栏完成注册：</p>
            <p>http://localhost/register/activeuser.php?verifyStr='.$verify_string.'</p>
            <p>请注意，如果您错误地收到了此电子邮件，请忽略这封邮件！此账户将不会启动。</p>
            </div>
            <div id="footer">
            <hr />
            <p>如需其它帮助，请联系我们：kindle@amzbook.com</p>
            <p>此邮件由系统自动发出，请勿回复。</p>
            </div>
        </div>
    </div>';
    postmail_jiucool_com($email,$subject = "感谢您注册Kindle阅读，请激活您的账户",$body);
    
    return true;
}

//验证更新
function verifyUser($verifyStr){
    $db = DBCxn::get();
    $st = $db->prepare('UPDATE users SET verifed=1 WHERE verifyStr=?');
    $st->execute(array($verifyStr));
    $rowCount = $st->rowCount();//查询影响行
    if($rowCount == 1){
        return true;
    } else{
        return false;
    }
}

?>