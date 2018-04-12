<?php
function config($name){
	$config=include('config.php');
	$config=$config[$name];
	return $config;
}

function siteinfo($infoname){
		$db=new pdosql;
		$info=$db->fetOne('Sysconf',"Sysconf_Id,Sysconf_Name,Sysconf_Value,Sysconf_Type","Sysconf_Name='".$infoname."' and Sysconf_Type=1",$orderby = false);
		return $info['Sysconf_Value'];
		$db->close();
}

function initinfo($app,$id){
	$db=new pdosql;
	if($id){
		$tplname=$db->fetOne('Article','*',"Article_Id='".$id."' and Article_Del=0",$orderby = false);
		if($tplname) $siteinfo=$this->tplinit($tplname['Article_TypeId']);;
	}else{
		$tplname=$db->fetOne('Channels','*',"Channels_Id='.$app.' and Channels_Close=0",$orderby = false);
		if($tplname) $sitetpl=$this->tplinit($tplname['Channels_TypeId']);
	}
	if(!$sitetpl){
		$array["sitetpl"]='index';
		$array["title"]=siteinfo('SiteName');
		$array["key"]=siteinfo('Keywords');
		$array["desc"]=siteinfo('Description');
		return $array;
	}
	$db->close();
}

function tplinit($tplname){
	$sitetypes=$db->fetOne('SiteTypes',"SiteTypes_Id,SiteTypes_Name,SiteTypes_Tpl,SiteTypes_Type","SiteTypes_Id='".$tplname."'",$orderby = false);
	return $sitetypes['SiteTypes_Tpl'];
		$db->close();
}

function menu($classid){
$db=new pdosql;
$Channels=$db->fetAll('Channels',"Channels_Id,Channels_ClassId,Channels_Name,Channels_TypeId,Channels_Url,Channels_Close,Channels_Sort","Channels_Close=0 and Channels_ClassId='".$classid."'","Channels_Sort asc" ,$condition);
return $Channels;
$db->close();
}

?>
