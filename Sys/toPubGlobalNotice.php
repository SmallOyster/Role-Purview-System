<?php
$NowUserName=getSess(Prefix."RealName");
$Notice=$GB_Sets->G("Notice_Content",2,"Global");
$PubTime=$GB_Sets->G("Notice_PubTime",2,"Global");

if(isset($_POST) && $_POST){
  $Password=$_POST['Password'];
  $NowUserid=GetSess(Prefix."Userid");
  
  $sql="SELECT Password,salt FROM sys_user WHERE Userid=?";
  $rs=PDOQuery($dbcon,$sql,[$NowUserid],[PDO::PARAM_INT]);
  $iptPW_indb=$rs[0][0]['Password'];
  $salt=$rs[0][0]['salt'];

  $iptPW=encryptPW($Password,$salt);
  
  if($iptPW != $iptPW_indb){
    die('<script>alert("身份认证失败！");history.go(-1);</script>');
  }
  
  $readytoPub=$_POST['GlobalNotice'];
  $readytoPub=TextFilter($readytoPub);
  $GB_Sets->S($readytoPub,"Notice_Content",2,"Global");
  $GB_Sets->S(date("Y-m-d H:i:s"),"Notice_PubTime",2,"Global");
  
  addLog($dbcon,"全局","发布全局公告",$NowUserName);
  
  die('<script>alert("发布成功！");history.go(-1);</script>');  
}
?>

<form method="post">
<div class="well col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center col-xs-10 col-xs-offset-1">
  <h3>发布全局公告</h3><br>
  <div class="col-md-offset-2" style="line-height:12px;">
    <center>
      <h4 style="color:green">上次发布时间：<br><b><?php echo $PubTime; ?></b></h4>
      <hr>
      <button type="button" onclick='$("#GlobalNotice").val("")' style="width:98%;height:25px">清 空</button>
      <br><br>
      <textarea class="form-control" name="GlobalNotice" id="GlobalNotice" rows="8" cols="27"><?php echo $Notice; ?></textarea>
    </center>
    <hr>
    <div class="input-group">
      <span class="input-group-addon">您的密码</span>
      <input type="password" class="form-control" name="Password">
      <span class="input-group-addon" id="forgot">&lt;</span>
    </div>
    <hr>
    <input type="button" class="btn btn-primary" value="返 回 首 页" onclick="window.location.href='index.php';" style="width:48%"> <input type="submit" class="btn btn-success" style="width:48%" value="确 认 发 布">
  </div>
</div>
</form>