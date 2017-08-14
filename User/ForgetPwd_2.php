<?php
require_once("../Functions/PDOConn.php");
require_once("../Functions/PublicFunc.php");

$GB_Sets=new Settings("../GlobalSettings.json");
define("Prefix",$GB_Sets->G("SessionPrefix",2,"System"));

$isVerify=getSess(Prefix."FGPW_isVerify");
$UserID=getSess(Prefix."FGPW_UserID");
$UserName=getSess(Prefix."FGPW_UserName");
if($isVerify!="1"){
  header("Location: ../index.php");
}

if(isset($_POST) && $_POST){
  $salt=getRanSTR(8);
  $ipt_New=$_POST['NewPW'];
  $ipt_Vrf=$_POST['VerifyPW'];
  
  if(strlen($ipt_New)<6){
    die("<script>alert('密码长度须大于6位！');history.go(-1);</script>");
  }
  if($ipt_New!=$ipt_Vrf){
    die("<script>alert('两次输入的密码不相同！');history.go(-1);</script>");
  }

  $NewPW=encryptPW($ipt_New,$salt);
  
  $sql="UPDATE sys_user SET Password=?,salt=?,OriginPassword='',Status=2 WHERE UserID=?";
  $rs=PDOQuery($dbcon,$sql,[$NewPW,$salt,$UserID],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_INT]);

  if($rs[1]==1){
    addLog($dbcon,"用户","[$UserName] 忘记密码",$UserName);
    die("<script>alert('忘记密码成功！\n请重新登录！');window.location.href='../index.php';</script>");
  }
}
?>

<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
  <img src="res/img/back.png" style="position:absolute;wIDth:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回">
  <h3>忘记密码</h3><br>
  <div class="col-md-offset-2" style="line-height:12px;">
      <div class="input-group">
        <span class="input-group-addon">用户名</span>
        <input value="<?php echo $UserName; ?>" disabled>
        <span class="input-group-addon" ID="forgot">&lt;</span>
      </div>
      <hr>
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
      <input type="submit" class="btn btn-success" style="width:100%" value="确 认">
  </div>
</div>
</form>
</body>
</html>