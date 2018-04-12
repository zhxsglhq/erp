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

function user_info($uid){
	$db=new pdosql;
	$user=$db->fetOne("Admin","Admin_Id as id,Admin_Name as name,Admin_UserCode as code, Admin_Close as close,Admin_Power as power,Admin_BranchId as branchid,Admin_DeptId as deptid,Admin_GroupId as groupid,Admin_AddTime as addtime","Admin_Id='".$uid."'");
	return $user;
	$db->close();
}

function applist($where){
	$db=new pdosql;
	$app=$db->fetAll("App","App_Id,App_ClassId,App_ClassQty,App_Title,App_Path,App_Ico,App_Remarks,App_Close,App_AddTime",$where);
	return $app;
	$db->close();
}
?>
