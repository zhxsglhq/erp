<?php
$config=include(ROOT_PATH . '/protected/Controllers/admin_Config.php');
require_once ROOT_PATH . '/protected/Controllers/Config_Define.php';
require_once 'db/setsql.php';
require_once 'upfile.class.php';
class admin
{
public function tpl()
{
  $tplid=$_GET['tplid'];
  $uid=$_SESSION["uid"];
        if(!$uid){
        	echo "<script type='text/javascript'>alert('请先登入！');location.href='index.php?r=index/login';</script>";
        	exit();
        	}
 if ($tplid!="index" && $tplid!="default" && $tplid!="search" && $tplid!="upload" && $tplid!="export" && $tplid!="view"){
  $app=one("table=[App] field=[App_Id,App_Url,App_Close] where=[App_Id='".$tplid."' && App_Close='0']");
  $admin_power=one("table=[Admin] field=[Admin_Id,Admin_Power,Admin_ClassId,Admin_TypeId,Admin_MDeptId] where=[Admin_Id='".$uid."']");
  $powers = explode(",", $admin_power["Admin_Power"]);
  if($admin_power["Admin_TypeId"]!='1'){
	if(in_array($tplid,$powers)){
		$tpl = $app['App_Url'];
	}else{
		exit('没有权限！');
	}
  }else{
	$tpl = $app['App_Url'];
  }
 }else{
 	$tpl=$tplid;
 }
  $form_post=md5(uniqid(rand(),TRUE));
  $_SESSION["form_post"]=$form_post;
  $tplfile=TPL_INDEX.$tpl.".php";
  if(!is_file($tplfile))
  {
    $error=Error( $tplid.'此功能不存在！');
    echo $error;
    exit();
  }
include $tplfile;//载入编译文件

}

}
?>
