<?php
if(isset($_SESSION['user'])){
    echo $_SESSION['user'];
    echo $_SESSION['access_token'];
}else{
    header('Location: ..\index.php');
}
?>