<?php
$login = "retuam";
$password = 'moller';
if (!isset($_SERVER['PHP_AUTH_USER'])) {
header("WWW-Authenticate: Basic realm=\"Admin");
header("HTTP/1.0 401 Unauthorized");
die ("for Admin only!\n");
exit;
}
if($_SERVER['PHP_AUTH_USER'] != $login and $_SERVER['PHP_AUTH_PW']!= $password){
header("WWW-Authenticate: Basic realm=\"Admin");
header("HTTP/1.0 401 Unauthorized");
die ("for Admin only!\n");
}

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL,"ru_RU.UTF-8");
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';
//defined('YII_DEBUG') or define('YII_DEBUG',true);
require_once($yii);
Yii::createWebApplication($config)->run();
?>
