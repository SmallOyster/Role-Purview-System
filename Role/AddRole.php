<?php
$rtnURL="index.php?file=Role&action=toList.php";
    
if(isset($_POST) && $_POST){
  $RoleName=$_POST['RoleName'];
  $Brief=$_POST['Brief'];
  $sql="INSERT INTO role_list(RoleName,Brief,isSuper) VALUES(?,?,'0')";
  $rs=PDOQuery($dbcon,$sql,[$RoleName,$Brief],[PDO::PARAM_STR,PDO::PARAM_STR]);

  if($rs[1]==1){
    echo "<script>alert('新增角色成功！');window.location.href='$rtnURL';</script>";
  }else{
    ErrCodeDie("500-ARF");
  }
}
?>

<form method="post">
<div class="well col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center col-xs-10 col-xs-offset-1">
  <img src="res/img/back.png" style="position:absolute;width:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回" >
  <h3>新增角色</h3><br>
  <div class="col-md-offset-2" style="line-height:12px;">
    <div class="input-group">
      <span class="input-group-addon">角色名称</span>
      <input type="text" class="form-control" name="RoleName">
      <span class="input-group-addon" id="forgot">&lt;</span>
    </div>
    <hr>
    <h4>角色介绍</h4><br>
    <textarea class="form-control" name="Brief"></textarea>
    <hr>
    <input type="submit" class="btn btn-success" style="width:100%" value="确 认 增 加">
  </div>
</div>
</form>