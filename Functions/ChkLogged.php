<?php

$url="User/Logout.php";

// 传参处理
$re_Param="";
$Param=isset($_GET)?$_GET:"";
foreach($Param as $Key=>$Value){
  $re_Param=$re_Param."&".$Key."=".$Value;
}
$re_Param=base64_encode($re_Param);
if($re_Param!=""){
  $url=$url."?re_Param=".$re_Param;
}

// 是否有登录
$isLogged=GetSess(Prefix."isLogged");
if($isLogged != "1") header("Location: $url");

// 如果是主页面，不做任何处理
if($file=="View" && $action=="Index.php"){}

elseif($AllPurview!=null){
  // 如果没有页面权限,查看是否为公有页面
  if(!in_array($NowMenuID,$AllPurview)){
    $Chk_sql="SELECT * FROM sys_menu WHERE MenuID=?";
    $Chk_rs=PDOQuery($dbcon,$Chk_sql,[$NowMenuID],[PDO::PARAM_INT]);
    
    if($Chk_rs[1]==1){
      $isPublic=$Chk_rs[0][0]['isPublic'];
    }else{
      // 不存在于菜单数据库，是公有页面
      $isPublic="1";
    }
    
    if($isPublic=="0"){
      die('<script>alert("对不起，您暂无权限访问本页面！\nTips：可尝试刷新页面！\n\n错误码：502");history.go(-1);</script>');
    }
  }
}
?>