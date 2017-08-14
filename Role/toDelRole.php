<?php
if(isset($_GET['RID']) && $_GET['RID']){
  $RoleID=$_GET['RID'];
  $rs1=PDOQuery($dbcon,"SELECT * FROM role_list WHERE RoleID=?",[$RoleID],[PDO::PARAM_INT]);
  
  if($rs1[0][0]['isSuper']!="0"){
    ErrCodedie("500-NP");
  }
  
  $sql="DELETE FROM role_list WHERE RoleID=?";
  $sql2="DELETE FROM role_purview WHERE RoleID=?";
  if(isset($_POST['sure']) && $_POST['sure']){
    $rs=PDOQuery($dbcon,$sql,[$RoleID],[PDO::PARAM_INT]);
    $rs2=PDOQuery($dbcon,$sql2,[$RoleID],[PDO::PARAM_INT]);
   
    $rtnURL="index.php?file=Role&action=toList.php";
    echo "<script>window.location.href='$rtnURL';</script>";
  }
}else{
  ErrCodedie("500-GTDA");
}
?>

<form method="post">
<center>
  <input type="submit" class="btn btn-danger" value="确认删除" name="sure">
  <input type="button" class="btn btn-success" value="取消操作" onclick="window.location.href='index.php?file=Role&action=toList.php';">
</center>
</form>