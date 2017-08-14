<?php
$rs=PDOQuery($dbcon,"SELECT * FROM sys_menu",[],[]);
$Total=sizeof($rs[0]);
$allMenu=array();

if(isset($_GET['RID'])){
  $Purview=PDOQuery($dbcon,"SELECT * FROM role_purview WHERE RoleID=?",[$_GET['RID']],[PDO::PARAM_STR]);
  $PurviewList=$Purview[0];
}else{
  $PurviewList=array();
}

for($i=0;$i<$Total;$i++){
  $MenuID=$rs[0][$i]['MenuID'];
  $FatherID=$rs[0][$i]['FatherID'];
  $Name=$rs[0][$i]['Menuname'];
  $allMenu[$i]['id']=(int)$MenuID;
  $allMenu[$i]['pId']=(int)$FatherID;
  $allMenu[$i]['name']=urlencode($Name);
  foreach($PurviewList as $Value){
    if($Value['PurvID']==$MenuID){
      $allMenu[$i]['checked']=true;
    }
  }
}

$str = urldecode(json_encode($allMenu));
echo $str;

?>