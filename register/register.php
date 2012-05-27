<?php
include("mailFunction.php");

//执行相关函数
if(isset($_POST['username2']) && isset($_POST['email2'])  && isset($_POST['password2']) ){
    $user = $_POST['username2'];
    $email = $_POST['email2'];
    $pass = sha1($_POST['password2']);
    
    if( sendMail($user,$email,$pass)){
        echo "邮件发送到您的邮箱！请检查邮件并激活账户！";
    }
    
}

?>