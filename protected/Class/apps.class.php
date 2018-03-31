<?php
$config=include(ROOT_PATH . '/protected/Controllers/Config.php');
require_once ROOT_PATH . '/protected/Controllers/Config_Define.php';
require_once 'db/setsql.php';
class apps
{
public function code()
{
	session_start();
	header("Content-type: image/png;charset=utf8");
	srand((double)microtime()*1000000);
	$im=imagecreate(60,26);
	$black=imagecolorallocate($im,255,2555,255);
	$white=imagecolorallocate($im,255,255,255);
	$gray=imagecolorallocate($im,255,255,255);
	imagefill($im,0,0,$gray);
	$_SESSION["string"]="create";
	$_SESSION["create"]="";
	for($i=0;$i<4;$i++){
	 $str=mt_rand(3,3);
	 $size=mt_rand(10,10);
	 $create=mt_rand(0,9);
	 $_SESSION["create"].=$create;
	 imagestring($im,$size,(5+$i*10),$str,$create,imagecolorallocate($im,rand(0,130),rand(0,130),rand(0,130)));
	}
	for($i=0;$i<200;$i++){
	  $randcolor=imagecolorallocate($im,rand(0,255),rand(0,255),rand(0,255));
	  imagesetpixel($im,rand()%70,rand()%50,$randcolor);
	}
	imagepng($im);
	imagedestroy($im);
}

}
?>
