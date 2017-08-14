<?php
require_once("../Functions/PDOConn.php");
require_once("../Functions/PublicFunc.php");

$GB_Sets=new Settings("../GlobalSettings.json");
define("Prefix",$GB_Sets->G("SessionPrefix",2,"System"));

$SessName=array(Prefix."isLogged",Prefix."UserID",Prefix."RoleID",Prefix."RealName",Prefix."RoleName",Prefix."isSuper");

if(isset($_POST) && $_POST){
  // 获取用户输入的数据
  $ipt_PW=$_POST['Password'];
  $ipt_UserName=$_POST['Name'];
  
  // 根据用户输入的用户名寻找对应资料
  $sql="SELECT * FROM sys_user WHERE UserName=?";
  $rs=PDOQuery($dbcon,$sql,[$ipt_UserName],[PDO::PARAM_STR]);
  
  // 无此用户
  if($rs[1]!=1){
    die();
  }

  // 获取用户资料
  $PW_indb=$rs[0][0]['Password'];
  $salt=$rs[0][0]['salt'];
  $UserID=$rs[0][0]['UserID'];
  $RoleID=$rs[0][0]['RoleID'];
  $RealName=$rs[0][0]['RealName'];
  $Status=$rs[0][0]['Status'];
  $originPassword=$rs[0][0]['originPassword'];
  
  // 用户被禁用
  if($Status==0){
    die("UserForbidden");
  }
  
  // 获取角色资料
  $roleinfo_sql="SELECT RoleName,isSuper FROM role_list WHERE RoleID=?";
  $roleinfo_rs=PDOQuery($dbcon,$roleinfo_sql,[$RoleID],[PDO::PARAM_INT]);
  $RoleName=$roleinfo_rs[0][0]['RoleName'];
  $isSuper=$roleinfo_rs[0][0]['isSuper'];
  
  // 将数据库里的输入的密码和salt合并加密
  $ipt_PW=encryptPW($ipt_PW,$salt);
  
  // 密码比对
  if($ipt_PW != $PW_indb){
    die();
  }else{
    $SessVal=array("1",$UserID,$RoleID,$RealName,$RoleName,$isSuper);
    
    // 设置Session
    SetSess($SessName,$SessVal);

    // 修改用户最后登录时间
    $Date=date("Y-m-d H:i:s");
    $rs2=PDOQuery($dbcon,"UPDATE sys_user SET LastDate=? WHERE UserID=?",[$Date,$UserID],[PDO::PARAM_STR,PDO::PARAM_INT]);

    // 缓存的基本操作
    $Cache=new Cache($dbcon,"login");
    $Cache->E();
    $SessionID=session_ID();
    $ExpTime=time()+600;// 10分钟后过期
    $IP=getIP();

    // 检查是否有重复登录
    $Condition[0]=array("UserID",$UserID);
    $OldCache=$Cache->G($Condition);
    if($OldCache[1]!=0){
      die("2".$OldCache[0][0]['CacheTime'].$OldCache[0][0]['IP']);
    }

    // 新增登录缓存
    $Cache_Param=array("SessionID","UserID","RealName","ExpTime","IP");
    $Cache_Value=array($SessionID,$UserID,$RealName,$ExpTime,$IP);
    $Cache_rs=$Cache->S($Cache_Param,$Cache_Value);

    // 新增登录日志
    addLog($dbcon,"登录","[$RealName] 登录系统",$ipt_UserName);

    // 返回跳转的URL
    if($_POST['re_Param']!=""){
      $re_Param=$_POST['re_Param'];
      $re_Param=base64_decode($re_Param);
      die("1"."../index.php?re=1".$re_Param);
    }elseif($Status==1){
      die("1"."../index.php?file=User&action=UpdatePersonalPW.php&isFirst=1&u={$ipt_UserName}&r={$RealName}");
    }else{
      die("1"."../index.php");
    }    
  }
}
?>
