<?php
include("../PDOConn.php");
if(isset($_POST) && $_POST){
if($_POST['type']==1){
  $rs=PDOQuery($dbcon,"SELECT * FROM role_list",[],[]);
  $Total=sizeof($rs[0]);
  $allRole=array();

  for($i=0;$i<$Total;$i++){
   $id=$rs[0][$i]['Roleid'];
   $name=$rs[0][$i]['RoleName'];
   $allRole[$i]['id']=$id;
   $allRole[$i]['name']=$name;
  }
  $str=urldecode(json_encode($allRole));
  echo $str;
}else{
  $id=$_POST['rid'];
  $rs=PDOQuery($dbcon,"SELECT * FROM role_list WHERE Roleid=?",[$id],[PDO::PARAM_INT]);
  $role[0]['name']=$rs[0][0]['RoleName'];
  $role[0]['remark']=$rs[0][0]['remark'];
  $str=urldecode(json_encode($role));
  echo $str;
}
}
?>