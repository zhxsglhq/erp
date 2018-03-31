<?php
function config($name){
	$config=include('config.php');
	$config=$config[$name];
	return $config;
}

function checkuser($username,$password){
	$db=new pdosql;
	$user=$db->fetOne("Admin","Admin_Id as id,Admin_Name as username" ,"Admin_UserCode='".$username."' and Admin_Password='".$password."' and Admin_Close=0");
	return $user;
	$db->close();
}
?>
