<?php
include("../PDOConn.php");
if(isset($_POST) && $_POST){
// type-1:所有角色
// type-null:指定角色
if($_POST['type']==1){
  $rs=PDOQuery($dbcon,"SELECT * FROM role_list",[],[]);
  $Total=sizeof($rs[0]);
  $allRole=array();

  for($i=0;$i<$Total;$i++){
   $ID=$rs[0][$i]['RoleID'];
   $name=$rs[0][$i]['RoleName'];
   $allRole[$i]['ID']=$ID;
   $allRole[$i]['name']=$name;
  }
  $str=urldecode(json_encode($allRole));
  echo $str;
}else{
  $ID=$_POST['rID'];
  $rs=PDOQuery($dbcon,"SELECT * FROM role_list WHERE RoleID=?",[$ID],[PDO::PARAM_INT]);
  $role[0]['name']=$rs[0][0]['RoleName'];
  $role[0]['brief']=$rs[0][0]['Brief'];
  $str=urldecode(json_encode($role));
  echo $str;
}
}
?>