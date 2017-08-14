<?php
include("Functions/OEA.php");
$OEA=new OEA();

$RealName=getSess(Prefix."RealName");

if(isset($_POST) && $_POST){
  if(!isset($_GET['isFirst']) || $_GET['isFirst']!=1){
    $ipt_PW=$_POST['Password'];
    $NowUserID=GetSess(Prefix."UserID");
  
    $sql1="SELECT Password,salt,UserName,RealName FROM sys_user WHERE UserID=?";
    $rs1=PDOQuery($dbcon,$sql1,[$NowUserID],[PDO::PARAM_INT]);
    $PW_indb=$rs1[0][0]['Password'];
    $salt=$rs1[0][0]['salt'];
    $UserName=$rs1[0][0]['UserName'];
    $RealName=$rs1[0][0]['RealName'];
    $iptPW=encryptPW($ipt_PW,$salt);
  
    if($iptPW != $PW_indb){
      die('<script>alert("原密码错误！");history.back();</script>');
    }
  }else{
    addLog($dbcon,"用户","强制修改密码",$RealName);
    $UserName=$_GET['u'];
    $RealName=$_GET['r'];
  }
  
  $salt=getRanSTR(8);
  $ipt_New=$_POST['NewPW'];
  $ipt_Vrf=$_POST['VerifyPW'];
  
  if(strlen($ipt_New)<6){
    die("<script>alert('密码长度须大于6位！');history.go(-1);</script>");
  }

  $NewPW=encryptPW($ipt_New,$salt);
  if($ipt_New!=$ipt_Vrf){
    die("<script>alert('两次输入的密码不相同！');history.go(-1);</script>");
  }
  
  $sql2="UPDATE sys_user SET Password=?,salt=?,OriginPassword='',Status=2 WHERE UserID=?";
  $rs2=PDOQuery($dbcon,$sql2,[$NewPW,$salt,$UserID],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_INT]);
  
  $ShowPwd=$OEA->Encrypt($ipt_New);
  if($rs2[1]==1){
    $url="index.php?file=User&action=ShowOriginPW.php&u=$UserName&r=$RealName&p={$ShowPwd[0]}&k={$ShowPwd[1]}";
    echo "<script>window.location.href='$url';</script>";
  }
}
?>

<form method="post">
<div class="well col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center col-xs-10 col-xs-offset-1">
  <img src="res/img/back.png" style="position:absolute;wIDth:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回">
  <h3>修改密码</h3><br>  
  <div class="col-md-offset-2" style="line-height:12px;">
    <?php if(!isset($_GET['isFirst']) || $_GET['isFirst']!=1){ ?>
    <div class="input-group" ID="OldPassword">
      <span class="input-group-addon">旧密码</span>
      <input type="password" class="form-control" name="Password">
      <span class="input-group-addon" ID="forgot">&lt;</span>
    </div>
    <hr>
    <?php } ?>
    <div class="input-group">
      <span class="input-group-addon">新密码</span>
      <input type="password" class="form-control" name="NewPW">
      <span class="input-group-addon" ID="forgot">&lt;</span>
    </div>
    <div class="input-group">
      <span class="input-group-addon">再次输入</span>
      <input type="password" class="form-control" name="VerifyPW">
      <span class="input-group-addon" ID="forgot">&lt;</span>
    </div>
    <hr>
    <input type="submit" class="btn btn-success" style="width:100%" value="确 认 重 置">
  </div>
</div>
</form>