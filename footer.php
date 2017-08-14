<?php
$BeginDay=strtotime($GB_Sets->G("SystemBeginRunDay",2,"System"));
$NowDay=strtotime(date("Ymd"));
$RunDays=round(($NowDay-$BeginDay)/3600/24);

if($RunDays>365){
  $Year=floor($RunDays/365);
  $RunDays=$RunDays-$Year*365;
}else{
  $Year=0;
}

if($RunDays>30){
  $Month=floor($RunDays/30);
  $RunDays=$RunDays-$Month*30;
}else{
  $Month=0;
}
?>

<div class="container">
  <hr>
  <center>
  <p style="font-weight:bolder;font-size:19;line-height:26px;">
    &copy; 生蚝科技 2014-<?php echo date("Y"); ?><br>
    All Rights Reserved.<br>
    系统已安全运行<font color="green"><?php echo $Year; ?></font>年<font color="green"><?php echo $Month; ?></font>月<font color="green"><?php echo $RunDays; ?></font>天
  </center>
</div>