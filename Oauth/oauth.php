<?php
require_once 'src/apiClient.php';
require_once 'src/contrib/apiPlusService.php';

session_start();
$client = new apiClient();
$client->setApplicationName("Kindle Reader");
$client->setScopes(array('https://www.google.com/reader/api'));

if (isset($_REQUEST['logout'])) {
  unset($_SESSION['access_token']);
}

if (isset($_GET['code'])) {
  $client->authenticate();
  $_SESSION['access_token'] = $client->getAccessToken();
  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['access_token'])) {
  $client->setAccessToken($_SESSION['access_token']);
}

if ($client->getAccessToken()) {
  $_SESSION['access_token']  = $client->getAccessToken();
  header('Location: sender.php');
} else {
  $status = "Logged Out";
  $authUrl = $client->createAuthUrl();
  header('Location: '.$authUrl);
}
?>
