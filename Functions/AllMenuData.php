
<?php
$rs=PDOQuery($dbcon,"SELECT * FROM sys_menu",[],[]);
$Total=sizeof($rs[0]);
$allMenu=array();

for($i=0;$i<$Total;$i++){
  $Menuid=$rs[0][$i]['Menuid'];
  $Fatherid=$rs[0][$i]['Fatherid'];
  $Name=$rs[0][$i]['Menuname'];
  $allMenu[$i]['id']=(int)$Menuid;
  $allMenu[$i]['pId']=(int)$Fatherid;
  $allMenu[$i]['name']=urlencode($Name);
  //var_dump(array_keys($allMenu[$i]));
  
}

$str = urldecode(json_encode($allMenu));
echo $str;

?>