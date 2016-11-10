<?php
$Nav_rs=PDOQuery($dbcon,"SELECT * FROM sys_menu WHERE Fatherid=0",[],[]);
$TotalFr=sizeof($Nav_rs[0]);
$AllPurv=GetSess("AllPurv");

$ShowMenuFile=array();
$ShowMenuDOS=array();
$ShowMenuName=array();
$HaveChd=0;
?>

<nav class="navbar navbar-default navbar-fixed-top"> 
<div class="container-fluid">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span> 
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="#">小生蚝角色权限系统</a>
  </div>
  
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
  <ul class="nav navbar-nav">
   <?php
   for($fri=0;$fri<$TotalFr;$fri++){
    $FatherNavid=$Nav_rs[0][$fri]['Menuid'];
    $FatherName=$Nav_rs[0][$fri]['Menuname'];
    //如果有该父菜单的权限
    if(in_array($FatherNavid,$AllPurv)){
     $Child_rs=PDOQuery($dbcon,"SELECT * FROM sys_menu WHERE Fatherid=?",[$FatherNavid],[PDO::PARAM_INT]);
     //如果有子菜单
     if($Child_rs[1]>0){
      //有多少个子菜单
      $Totalchd=sizeof($Child_rs[0]);
      $nowchd=0;
      for($chd=0;$chd<$Totalchd;$chd++){
       //如果没有该子菜单的权限
       if(in_array($Child_rs[0][$chd]['Menuid'],$AllPurv)){       
        $HaveChd++;       
        $ShowMenuFile[$fri][$nowchd]=$Child_rs[0][$chd]['PageFile'];
        $ShowMenuDOS[$fri][$nowchd]=$Child_rs[0][$chd]['PageDOS'];
        $ShowMenuName[$fri][$nowchd]=$Child_rs[0][$chd]['Menuname'];
        $nowchd++;
       }else{}
      }
     }else{
      //没有子菜单
      $HaveChd=0;
     }
    }else{
     $HaveChd=-1;
    }
    
    if(in_array($FatherNavid,$AllPurv)){
    if($HaveChd==0){
     $NavFile=$Nav_rs[0][$fri]['PageFile'];
     $NavAction=$Nav_rs[0][$fri]['PageDOS'];
   ?>
   <li><a href="<?php echo '?file='.$NavFile.'&action='.$NavAction; ?>"><i class="fa fa-newspaper-o" aria-hidden="true"></i> <?php echo $FatherName; ?></a></li>
   <?php }else{ ?>
   <li class="dropdown">
    <a href="" data-target="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users" aria-hidden="true"></i> <?php echo $FatherName; ?><b class="caret"></b></a>
    <ul class="dropdown-menu">
    <?php
    $TTshowChd=sizeof($ShowMenuName[$fri]);
    for($j=0;$j<$TTshowChd;$j++){
     $nowfile=$ShowMenuFile[$fri][$j];
     $nowdos=$ShowMenuDOS[$fri][$j];
    ?>
    <li><a href="<?php echo '?file='.$nowfile.'&action='.$nowdos; ?>"><i class="fa fa-newspaper-o" aria-hidden="true"></i> <?php echo $ShowMenuName[$fri][$j]; ?></a></li>
    <?php } ?>
    </ul>
   </li>
   <?php } }} ?>
  </ul>
  
  <ul class="nav navbar-nav navbar-right">
    <li>
      <center>
        <b><font color="green">无名氏</font></b>，欢迎回来
        <p><span style="color:#4fb4f7;margin-right:8px">角色：<?php echo GetSess("RoleName");?> · <a href="?file=User&action=Logout">退出登陆</a>
      </center>
    </li>
  </ul>
</div>
</div>
</nav>
<style>body{padding-top:70px;}</style>