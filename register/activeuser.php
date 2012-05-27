<?php
include("mailFunction.php");

if(isset($_GET['verifyStr']) ){
    $verifyStr = trim($_GET['verifyStr']);
    if( verifyUser($verifyStr) ==1 ){
        echo "恭喜，您的账户已经激活成功，感谢您使用我们的服务！";
    } else{
        echo "链接失效！如果您已经激活账户，请直接登录。";
    }
} else{
        echo "链接错误，请点击邮件中的链接";
}

?>