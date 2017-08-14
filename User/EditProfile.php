<?php
$UserID=isset($_GET['UID'])?$_GET['UID']:"";
$UserName=isset($_GET['UserName'])?$_GET['UserName']:"";
$RealName=isset($_GET['RealName'])?$_GET['RealName']:"";

if($UserID=="" || $UserName=="" || $RealName=="") ErrCodedie("500");

if(isset($_POST) && $_POST){
  $UserName=$_POST['UserName'];
  $RealName=$_POST['RealName'];
  $sql="UPDATE sys_user SET UserName=?,RealName=? WHERE UserID=?";
  $rs=PDOQuery($dbcon,$sql,[$UserName,$RealName,$UserID],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_INT]);

  if($rs[1]==1){
    echo "<script>alert('修改成功！');window.location.href='index.php?file=User&action=toList.php';</script>";
  }else{
    echo "<script>alert('修改失败！！！');window.location.href='index.php?file=User&action=toList.php';</script>";
  }
}
?>

<form method="post">
<div class="well col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center col-xs-10 col-xs-offset-1">
  <img src="res/img/back.png" style="position:absolute;wIDth:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回" >
  <h3>编辑用户资料</h3><br>
  <div class="col-md-offset-2" style="line-height:12px;">
      <div class="input-group">
        <span class="input-group-addon">用户名</span>
        <input type="text" class="form-control" name="UserName" ID="UserName" value="<?php echo $UserName; ?>">
        <span class="input-group-addon" ID="forgot">&lt;</span>
      </div>
      <div class="input-group">
        <span class="input-group-addon">真实姓名</span>
        <input type="text" class="form-control" name="RealName" ID="RealName" value="<?php echo $RealName; ?>">
        <span class="input-group-addon" ID="forgot">&lt;</span>
      </div>
      <hr>
      <input type="submit" class="btn btn-success" style="wIDth:100%" value="确 认 修 改">
  </div>
</div>
</form>