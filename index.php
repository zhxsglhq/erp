<?php
session_start();
setcookie("tmp", 100, NULL, NULL, NULL, NULL, TRUE);
ini_set('session.cookie_httponly',true);
ini_set('session.cookie_secure',true);
ini_set('session.use_only_cookies',true);
error_reporting(E_ALL & ~ E_NOTICE);
header('X-Frame-Options:SAMEORIGIN');
//header("Access-Control-Allow-Origin: *");
header("Content-type:text/html;charset=utf-8");
date_default_timezone_set('PRC');
!defined('ROOT_PATH') && define('ROOT_PATH', str_replace('\\', '/', dirname(__FILE__)));
require_once ROOT_PATH . '/protected/Class/Error.class.php';
require_once ROOT_PATH . '/protected/Class/Safe.class.php';
$sqlsafe = new sqlsafe();
$sqlsafe->webscan();
require_once ROOT_PATH . '/protected/Init.php';
?>
