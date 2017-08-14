<?php
require_once("../Functions/PDOConn.php");
require_once("../Functions/PublicFunc.php");

$GB_Sets=new Settings("../GlobalSettings.json");
define("Prefix",$GB_Sets->G("SessionPrefix",2,"System"));

if(isset($_POST) && $_POST){
  $UserName=$_POST['UserName'];
  $Phone=$_POST['Phone'];
  $Email=$_POST['Email'];
  
  $Info_rs=PDOQuery($dbcon,"SELECT * FROM sys_user WHERE UserName=?",[$UserName],[PDO::PARAM_STR]);
  if($Info_rs[1]!=1){
    die('<script>alert("无此用户或用户名错误！");history.go(-1);</script>');
  }

  $UserID=$Info_rs[0][0]['UserID'];

  if($Phone!=$Info_rs[0][0]['Phone'] || $Email!=$Info_rs[0][0]['Email']){
    die('<script>alert("身份认证失败！");history.go(-1);</script>');
  }

  setSess(Prefix."FGPW_isVerify","1");
  setSess(Prefix."FGPW_UserID",$UserID);
  setSess(Prefix."FGPW_UserName",$UserName);

  die('<script>window.location.href="ForgetPwd_2.php";</script>');
}
?>

<html>
<head>
  <meta name="viewport" content="wIDth=device-width, initial-scale=1">
  <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
  <script type="text/javascript" src="https://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
  <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="../res/js/utils.js"></script>
  <title>忘记密码 / 小生蚝角色权限系统</title>
</head>

<body>
<br>
<form method="post">
<div class="well col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center col-xs-10 col-xs-offset-1">
  <img src="../res/img/back.png" style="position:absolute;wIDth:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回">
  <h3>忘 记 密 码</h3><br>
  <div class="alert alert-warning alert-dismissible" role="alert">
    请按提示输入资料以认证身份！<br>
    感谢您的配合！<br>
  </div>
  <div class="col-md-offset-2" style="line-height:12px;">
    <div class="input-group">
      <span class="input-group-addon">用户名</span>
      <input class="form-control" name="UserName">
      <span class="input-group-addon" ID="forgot">&lt;</span>
    </div>
    <div class="input-group">
      <span class="input-group-addon">手机号</span>
      <input class="form-control" name="Phone">
      <span class="input-group-addon" ID="forgot">&lt;</span>
    </div>
    <div class="input-group">
      <span class="input-group-addon">邮箱</span>
      <input type="text" class="form-control" name="Email">
      <span class="input-group-addon" ID="forgot">&lt;</span>
    </div>
    <hr>
    <input type="button" class="btn btn-primary" value="取 消 操 作" onclick='window.close();' style="width:48%"> <input type="submit" class="btn btn-success" style="wIDth:48%" value="下 一 步"> 
  </div>
</div>
</form>

<script>
window.onload=function(){
  $("input").attr("autocomplete","off");
}
</script>
</body>
</html>