<?php
include("Functions/OEA.php");
$OEA=new OEA();

$UserID=@$_GET['UID'];
$UserName=@$_GET['n'];
$RealName=@$_GET['r'];
$LocAuth=getSess(Prefix."inUserList");
$NowUserName=getSess(Prefix."RealName");

if($LocAuth!="1") ErrCodedie("404");
if(!$UserID || !$UserName || !$RealName) ErrCodedie("500");

if(isset($_POST) && $_POST){
  $iptPW=$_POST['Password'];
  $NowUserID=GetSess(Prefix."UserID");
  
  $sql1="SELECT Password,salt FROM sys_user WHERE UserID=?";
  $rs1=PDOQuery($dbcon,$sql1,[$NowUserID],[PDO::PARAM_INT]);
  $iptPW_indb=$rs1[0][0]['Password'];
  $salt=$rs1[0][0]['salt'];
  
  $iptPW=encryptPW($iptPW,$salt);
  
  if($iptPW != $iptPW_indb){
    die('<script>alert("身份认证失败！");window.location.href="index.php";</script>');
  }
  
  $PW_arr=GetRanPW();
  $OriginPassword=$PW_arr[0];
  $salt=$PW_arr[1];
  $PW_db=$PW_arr[2];

  $sql2="UPDATE sys_user SET Password=?, salt=?,originPassword=?,Status=1 WHERE UserID=?";
  $rs2=PDOQuery($dbcon,$sql2,[$PW_db,$salt,$OriginPassword,$UserID],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_INT]);

  if($rs2[1]==1){
    addLog($dbcon,"用户","[$RealName] 被重置密码",$NowUserName);
    $ShowPwd=$OEA->Encrypt($ipt_New);
    $url="index.php?file=User&action=ShowOriginPW.php&u=$UserName&r=$RealName&p={$ShowPwd[0]}&k={$ShowPwd[1]}&re_file=User&re_action=toList.php";
    echo "<script>window.location.href='$url';</script>";    
  }
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
      <input type="submit" class="btn btn-success" style="wIDth:100%" value="确 认 重 置">
  </div>
</div>
</form>
