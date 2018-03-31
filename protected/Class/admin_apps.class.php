<?php
$uid=$_SESSION["uid"];

if (!$uid){exit();}
$config=include(ROOT_PATH . '/protected/Controllers/admin_Config.php');
require_once ROOT_PATH . '/protected/Controllers/Config_Define.php';
require_once 'tree.class.php';
require_once 'db/setsql.php';
class admin_apps{
	function __construct(){
		$this->uid= $_SESSION["uid"];
		$this->session_post = $_SESSION["form_post"];
		$this->form_post = $_POST['form_post'];
		$this->apps = $_POST['apps'];
		if (!$this->apps){
			$this->apps = $_GET['apps'];
		}
		$this->id = $_POST['id'];
		if (!$this->id){
			$this->id = $_GET['id'];
		}
		$this->notviewid = $_POST['notviewid'];
		$this->viewid = $_POST['viewid'];
		if (!$this->viewid){
			$this->viewid = $_GET['viewid'];
		}
		$this->onfield=$_POST['onfield'];
		if (!$this->onfield){
			$this->onfield = $_GET['onfield'];
		}
		$this->vid=$_POST['vid'];
		if (!$this->vid){
			$this->vid = $_GET['vid'];
		}
		$this->admin_mdept=one("table=[Admin] field=[Admin_Id,Admin_MDeptID,Admin_ClassId,Admin_TypeId] where=[Admin_Id='".$this->uid."']");
		$this->mdept_id=$this->admin_mdept["Admin_MDeptID"];
		//if ($this->form_post==$this->session_post){
			$this->apps_table=one("table=[App] field=[App_Id,App_ClassId,App_Field,App_Table,App_Close] where=[App_Id='".$this->apps."']");

			if($this->apps_table){
				$this->table = $this->apps_table["App_Table"];
				$this->appid = $this->apps_table["App_ClassId"];

				if($this->onfield){
					if($this->id){
						$sid=$this->id;
					}else{
						$sid=0;
					}
					$this->rows=all("table=[".$this->table."] where=[".$this->onfield." in (".$sid.") ] orderby=[".$this->table."_Id desc]");


					if ($this->apps==104){
						foreach($this->rows as $this->rows){
							$CourseTime .=$this->rows[$this->table.'_Start']."-".$this->rows[$this->table.'_Stop']."";
						}
						echo $CourseTime;
					}else{
						echo $this->rows[0][$this->table.'_Name'];
					}
					exit();
				}
				if($this->apps_table['App_Field']){
					$search=all("table=[Field] where=[Field_Id in (".$this->apps_table['App_Field'].") && Field_Close='0'  && (Field_Type='text' or Field_Type='datetime') && Field_View='0'] orderby=[Field_Id,Field_Sort]");
					foreach($search as $search){
						if($search['Field_Type']=='datetime'){
							if (!empty($_POST[$search['Field_Field']."_Start"]) && !empty($_POST[$search['Field_Field']."_Stop"])){
								$this->where .= " and ".$search['Field_Field']." between '".$_POST[$search['Field_Field']."_Start"]."' and '".$_POST[$search['Field_Field']."_Stop"]."'";
							}
						}else{
							if ($_POST[$search['Field_Field']]){
								if($search['Field_Field']=='ViewStudent_ChannelName'){
									$this->where .= " and ViewStudent_ChannelId = '".$_POST['ViewStudent_ChannelName']."'";
								}elseif($search['Field_Field']=='ViewStudent_Age'){
									$ages = explode("-", $_POST['ViewStudent_Age']);
									$this->where .= " and  ViewStudent_Age >=".$ages[0]." && ViewStudent_Age <=".$ages[1]."";
								}else{
									$this->where .= " and ".$search['Field_Field']." like '%".$_POST[$search['Field_Field']]."%'";
								}
							}
						}
					}
				}

                if ($_POST['Adviser']==1){
									$this->where .= " and ViewStudent_Adviser>'0'";
                }elseif($_POST['Adviser']==2){
                    $this->where .= " and ViewStudent_AdviserId ='0'";
                }
								if ($this->viewid){
									$this->where .= "and ".$this->table."_Id=".$this->viewid;
								}
				if($this->table=='MDept'){
					$this->mdept_classid=one("table=[MDept] field=[MDept_Id,MDept_ClassId] where=[MDept_Id='".$this->mdept_id."']");
					$this->rows=page("table=[MDept] where=[MDept_Id>'1' ".$this->where."] orderby=[".$this->table."_Id desc]");
				}elseif($this->table=='Department'){
					$this->rows=page("table=[Department] where=[Department_Id>'1' && Department_MDeptId='".$this->mdept_id."' ".$this->where."] orderby=[".$this->table."_Id desc]");
				}elseif($this->table=='Admin'){
					$this->rows=page("table=[Admin] where=[Admin_Id>'1' ".$this->where."] orderby=[".$this->table."_Id desc]");
				}elseif($this->table=='App'){
					$this->rows=all("table=[App] where=[App_ClassQty>'0']");
				}elseif($this->table=='Login_Logs'){
					$this->rows=page("table=[Login_Logs] where=[Login_Logs_Close='0' ".$this->where."] orderby=[Login_Logs_Id desc]");
				}elseif($this->apps_table["App_Id"]=='86'){
					$this->rows=page("table=[TrackType] where=[TrackType_Close='0']");
				}elseif($this->apps_table["App_Id"]=='87'){
					$this->rows=page("table=[TrackType] where=[TrackType_Close='1']");
				}elseif($this->apps_table["App_Id"]=='108'){
					$this->rows=page("table=[CourseTime] where=[CourseTime_Close='0']");
				}elseif($this->apps_table["App_Id"]=='118'){
					$this->rows=page("table=[Week]");
				}elseif($this->table=='Channel'){
					$this->rows=page("table=[Channel] where=[Channel_Id>'1' && Channel_MDeptId='".$this->mdept_id."' ".$this->where."] orderby=[".$this->table."_Id desc]");
				}else{
					if ($this->vid){
						$this->where .= "and ".$this->table."_StudentId=".$this->vid;
					}

					if($this->apps=='103' or $this->apps=='101'){
						$this->where .=" and ".$this->table."_TrackId='7'";
					}
					if($this->admin_mdept['Admin_TypeId']!=1){
						if($this->apps=='82' or $this->apps=='110' or $this->apps=='112' or $this->apps=='102' or $this->apps=='103'){
							$this->where .=" and ".$this->table."_AdviserId=".$this->admin_mdept['Admin_Id'];
						}
					}
					if($this->admin_mdept['Admin_TypeId']!=1){
						if($this->apps=='73'){
							$this->where .=" and ".$this->table."_AddAdminId=".$this->admin_mdept['Admin_Id'];
						}
					}
					//if($this->admin_mdept['Admin_ClassId']!=1){
						//if($this->apps=='72'){
							//$this->where .=" and ".$this->table."_AddAdminId=".$this->admin_mdept['Admin_Id'];
						//}
					//}
					$field=all("table=[Field] field=[Field_Field,Field_Id,Field_View,Field_Close] where=[Field_Id in (".$this->apps_table['App_Field'].") && Field_Close='0']");
					foreach($field as $field){
						$fields .= $field['Field_Field'].',';
					}
					$fields= substr($fields, 0, -1);
					$this->rows=page("table=[".$this->table."] field=[$fields] where=[".$this->table."_MDeptId='".$this->mdept_id."' ".$this->where."] orderby=[".$this->table."_Id desc]");
				}
			}
		//}
	}

public function treegrid()
{
if ($this->rows){
	if($this->mdept_classid){
		$this->treestart=$this->mdept_classid['MDept_ClassId'];
	}else{
		$this->treestart='2';
	}

  $bta = new BuildTreeArray($this->rows[0],$this->table,$this->table.'_Id',$this->table.'_ClassId',$this->treestart);
	$data = $bta->getTreeArray();
	echo json_encode($data);
}else{
	$form_post=md5(uniqid(rand(),TRUE));
	$_SESSION["form_post"]=$form_post;
}
}

public function onname()
{
if ($this->rows){
	echo $this->rows[0][$this->table.'_Name'];
}
}

public function views()
{
	$array['total'] = $this->rows[1];
	$array['rows'] = $this->rows[0];
	echo json_encode($array);
}


public function poweredits()
{
if ($this->form_post==$this->session_post){
		$db=new pdosql;
		$post_power = implode(",",$_POST['power']);
		$power=array (Admin_Power => $post_power);
		$edits=$db->update('Admin',$power,'Admin_Id='.$this->id);
		if ($edits=='1'){
			echo json_encode("ok");
		}else{
			echo json_encode("<i class=\"fa fa-times-circle\"></i>内容无变化或编码、名称重复，修改失败！");
		}
		$db->close();
}
}

public function tree()
{
	if ($this->rows){
		if($this->mdept_classid){
			$this->treestart=$this->mdept_classid['MDept_ClassId'];
		}elseif($this->table=='Channel'){
			$this->treestart='2';
		}elseif($this->table=='Admin'){
			$this->treestart='2';
		}else{
			$this->treestart='2';
		}
		foreach($this->rows[0] as $v){
			if($this->notviewid){
				if($v[$this->table.'_Id'] != $this->notviewid && $v[$this->table.'_ClassId'] != $this->notviewid){
						$rows[]=$v;
				}
			}elseif($this->viewid){
				if($v[$this->table.'_Id'] == $this->viewid){
						$rows[]=$v;
				}
			}else{
				$rows=$this->rows[0];
			}
		}
		//echo json_encode($rows);
	  $bta = new BuildTreeArray($rows,$this->table,$this->table.'_Id',$this->table.'_ClassId',$this->treestart);
		$data = $bta->getTreeArray();
		$data=json_encode($data);
		$tableid= str_replace($this->table."_Id","id",$data);
		$tablename=str_replace($this->table."_Name","text",$tableid);
		echo $tablename;
	}
}

public function adds()
{
	if($this->apps_table){
		$field=all("table=[Field] field=[Field_Field,Field_Type,Field_Maxlength,Field_Required] where=[Field_Id in (".$this->apps_table['App_Field'].") && Field_View='0' && Field_Close='0']");
		if(!empty($field)){
			foreach($field as $field){
				$table_field=$field['Field_Field'];
				$post=$_POST[$table_field];
				if($post){
					$post_field.=$table_field."=".$post.",";
				}
			}
		}
		$array_len=explode(',',rtrim($post_field, ","));
		for($i=0;$i<count($array_len);$i++){
			$field_array=explode('=',$array_len[$i]);
			$posts[$field_array[0]]=$field_array[1];
		}
		$db=new pdosql;
		$add=$db->add($this->table,$posts);
		if ($add){
			echo json_encode("ok");
		}else{
			echo json_encode("<i class=\"fa fa-times-circle\"></i>编码或名称重复，添加失败！");
		}
		$db->close();
	}
}

public function edits()
{
	if($this->apps_table){
		$field=all("table=[Field] field=[Field_Field,Field_Type,Field_Maxlength,Field_Required] where=[Field_Id in (".$this->apps_table['App_Field'].")  && Field_View='0' && Field_Close='0']");
		if(!empty($field)){
			foreach($field as $field){
				$table_field=$field['Field_Field'];
				$post=$_POST[$table_field];
				$post_field.=$table_field."=".$post.",";
			}
		}
		$array_len=explode(',',rtrim($post_field, ","));
		for($i=0;$i<count($array_len);$i++){
			$field_array=explode('=',$array_len[$i]);
			$posts[$field_array[0]]=$field_array[1];
		}
		if($_POST[$this->table.'_ClassId']==$this->id){
			exit(json_encode("上级信息不能为自己！"));
		}
		$db=new pdosql;
		$edits=$db->update($this->table,$posts,$this->table.'_Id='.$this->id." &&  ".$this->table.'_ClassId!='.$this->id);
		if ($edits=='1'){
			echo json_encode("ok");
		}else{
			echo json_encode("<i class=\"fa fa-times-circle\"></i>内容无变化或编码、名称重复，修改失败！");
		}
		$db->close();
	}
}

public function allot()
{

		$upAdviser=array('Student_AdviserId'=>$_POST['Student_AdviserId'],'Student_PhoneAdviserId'=>$_POST['Student_PhoneAdviserId'],'Student_TalkerAdviserId'=>$_POST['Student_TalkerAdviserId']);
		$db=new pdosql;
		$StudentId = explode(',',$this->id);
for($i=0;$i<count($StudentId);$i++){
$edits=$db->update('Student',$upAdviser,'Student_Id='.$StudentId[$i]);
}
		echo json_encode("ok");
		$db->close();
}

public function dels()
{
	if($this->apps_table){
		$db=new pdosql;
		$del=$db->delete($this->table,$this->table.'_Id='.$this->id);
		if ($del){
			echo json_encode("ok");
		}else{
			echo json_encode("<i class=\"fa fa-times-circle\"></i>此信息不存在或已被使用，删除失败！");
		}
		$db->close();
	}
}

}
?>
