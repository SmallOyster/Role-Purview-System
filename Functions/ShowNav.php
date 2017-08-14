<?php
$Nav_rs=PDOQuery($dbcon,"SELECT * FROM sys_menu WHERE FatherID=0",[],[]);
$TotalFr=sizeof($Nav_rs[0]);
$AllPurv=GetSess(Prefix."AllPurv");

$ShowMenuFile=array();
$ShowMenuDOS=array();
$ShowMenuName=array();
$ShowMenuIcon=array();
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
    <a class="navbar-brand" href="index.php">东风东游泳队管理系统</a>
  </div>
  
  <div class="collapse navbar-collapse" ID="bs-example-navbar-collapse-1">
  <ul class="nav navbar-nav">
   <?php
   for($fri=0;$fri<$TotalFr;$fri++){
    $FatherNavID=$Nav_rs[0][$fri]['MenuID'];
    $FatherName=$Nav_rs[0][$fri]['Menuname'];
    $FatherIcon=$Nav_rs[0][$fri]['MenuIcon'];
    //如果有该父菜单的权限
    if(in_array($FatherNavID,$AllPurv)){
     $HaveChd=0;
     $Child_rs=PDOQuery($dbcon,"SELECT * FROM sys_menu WHERE FatherID=?",[$FatherNavID],[PDO::PARAM_INT]);
     //如果有子菜单
     if($Child_rs[1]>0){
      // 有多少个子菜单
      $Totalchd=sizeof($Child_rs[0]);
      $nowchd=0;
      for($chd=0;$chd<$Totalchd;$chd++){
       // 如果有该子菜单的权限
       if(in_array($Child_rs[0][$chd]['MenuID'],$AllPurv)){
        $HaveChd++;
        $ShowMenuFile[$fri][$nowchd]=$Child_rs[0][$chd]['PageFile'];
        $ShowMenuDOS[$fri][$nowchd]=$Child_rs[0][$chd]['PageDOS'];
        $ShowMenuName[$fri][$nowchd]=$Child_rs[0][$chd]['Menuname'];
        $ShowMenuIcon[$fri][$nowchd]=$Child_rs[0][$chd]['MenuIcon'];
        $nowchd++;
       }
      }
     }else{
      // 没有子菜单
      $HaveChd=0;
     }
    }else{
     $HaveChd=-1;
    }
    
    if(in_array($FatherNavID,$AllPurv)){
    if($HaveChd==0){
     $NavFile=$Nav_rs[0][$fri]['PageFile'];
     $NavAction=$Nav_rs[0][$fri]['PageDOS'];
     $NavIcon=$Nav_rs[0][$fri]['MenuIcon'];
   ?>
   <li><a href="<?php echo '?file='.$NavFile.'&action='.$NavAction; ?>"><i class="fa fa-<?php echo $NavIcon; ?>" aria-hIDden="true"></i> <?php echo $FatherName; ?></a></li>
   <?php }else{ ?>
   <li class="dropdown">
    <a href="" data-target="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-<?php echo $FatherIcon; ?>" aria-hIDden="true"></i> <?php echo $FatherName; ?><b class="caret"></b></a>
    <ul class="dropdown-menu">
    <?php
    $TTshowChd=sizeof($ShowMenuName[$fri]);
    for($j=0;$j<$TTshowChd;$j++){
     $nowfile=$ShowMenuFile[$fri][$j];
     $nowdos=$ShowMenuDOS[$fri][$j];
     $nowicon=$ShowMenuIcon[$fri][$j];
    ?>
    <li><a href="<?php echo '?file='.$nowfile.'&action='.$nowdos; ?>"><i class="fa fa-<?php echo $nowicon; ?>" aria-hIDden="true"></i> <?php echo $ShowMenuName[$fri][$j]; ?></a></li>
    <?php } ?>
    </ul>
   </li>
   <?php } } } ?>
  </ul>
  
  <ul class="nav navbar-nav navbar-right">
    <li class="dropdown">
      <a href="" data-target="#" class="dropdown-toggle" data-toggle="dropdown"><img style="wIDth:22px;border-radius:9px;" src="res/img/user.png"></a>
      <ul class="dropdown-menu">
        <li><a href="javascript:voID(0)">
          <b><font color="green"><?php echo $RealName; ?></font></b>，欢迎回来！
        </a></li>
        <li class="divider"></li>
        <li><a href="javascript:voID(0)">
          角色：<font color="#F57C00"><?php echo $RoleName; ?></font>
        </a></li>
        <li class="divider"></li>
        <li><a href="index.php?file=User&action=UpdatePersonalPW.php">修改您的密码</a></li>
        <li><a href="User/Logout.php">安全退出系统</a></li>
      </ul>
    </li>
  </ul>
</div>
</div>
</nav>