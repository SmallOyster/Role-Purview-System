<?php
$NowUserName=getSess(Prefix."RealName");
$rtnURL="index.php?file=Sys&action=toLogList.php";

if(isset($_POST) && $_POST){
  $iptPW=$_POST['Password'];
  $NowUserID=GetSess(Prefix."UserID");
  
  $sql1="SELECT Password,salt FROM sys_user WHERE UserID=?";
  $Verify_rs=PDOQuery($dbcon,$sql1,[$NowUserID],[PDO::PARAM_INT]);
  $iptPW_indb=$Verify_rs[0][0]['Password'];
  $salt=$Verify_rs[0][0]['salt'];

  $iptPW=encryptPW($iptPW,$salt);
  
  if($iptPW != $iptPW_indb){
    die('<script>alert("身份认证失败！");history.go(-1);</script>');
  }
  
  $rs=PDOQuery($dbcon,"TRUNCATE sys_log",[],[]);
 
  addLog($dbcon,"系统","清空系统操作记录",$NowUserName);
  
  echo "<script>alert('清空成功！');window.location.href='$rtnURL';</script>";
  
}
?>

<form method="post">
<div class="well col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center col-xs-10 col-xs-offset-1">
  <img src="res/img/back.png" style="position:absolute;wIDth:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回">
  <h3>身份认证</h3><br>
    <div class="alert alert-warning alert-dismissible" role="alert">
    请输入您的密码以认证您的身份！感谢配合！
  </div>
  <div class="col-md-offset-2" style="line-height:12px;">
      <div class="input-group">
        <span class="input-group-addon">您的密码</span>
        <input type="password" class="form-control" name="Password">
        <span class="input-group-addon" ID="forgot">&lt;</span>
      </div>
      <hr>
      <input type="submit" class="btn btn-danger" style="wIDth:48%" value="确 认 清 空"> <input type="button" class="btn btn-success" value="取 消 操 作" onclick='window.location.href="<?php echo $rtnURL; ?>";' style="wIDth:48%">
  </div>
</div>
</form>