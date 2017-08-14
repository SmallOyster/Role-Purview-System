<?php
require_once("../Functions/PDOConn.php");
require_once("../Functions/PublicFunc.php");

$GB_Sets=new Settings("../GlobalSettings.json");
define("Prefix",$GB_Sets->G("SessionPrefix",2,"System"));

$SessName=array(Prefix."Reg_isReg",Prefix."Reg_UserID",Prefix."Reg_RealName",Prefix."Reg_Sign");
$Sign=getRanSTR(16);

if(isset($_POST) && $_POST){
  
  // 获取用户输入的数据
  $PW_ipt=$_POST['Password'];
  $UserName_ipt=$_POST['Name'];
  $RealName_ipt=$_POST['RealName'];
  $Phone_ipt=$_POST['Phone'];
  $Email_ipt=$_POST['Email'];
  $salt=getRanSTR(8);
  $indb_PW=encryptPW($PW_ipt,$salt);
  
  // 根据用户输入的用户名寻找对应资料
  $sql="SELECT UserID FROM sys_user WHERE UserName=?";
  $rs=PDOQuery($dbcon,$sql,[$UserName_ipt],[PDO::PARAM_STR]);
 
  // 已经存在同名用户
  if($rs[1]!=0){
    die("HaveUser");
  }

  // 获取默认角色ID
  $sql2="SELECT RoleID FROM role_list WHERE isDefault='1'";
  $rs2=PDOQuery($dbcon,$sql2,[],[]);

  if($rs2[1]!=1){
    die("NoRole");
  }
  
  $RoleID=$rs2[0][0]['RoleID'];
  
  // 新增用户
  $sql3="INSERT INTO sys_user SET UserName=?,RealName=?,Phone=?,Email=?,Password=?,Salt=?,RoleID=?,Status='2'";
  $rs3=PDOQuery($dbcon,$sql3,[$UserName_ipt,$RealName_ipt,$Phone_ipt,$Email_ipt,$indb_PW,$salt,$RoleID],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR]);
  
  // 新增失败
  if($rs3[1]!=1){
    die("InsertErr");
  }
  
  // 获取用户ID
  $sql4="SELECT UserID FROM sys_user WHERE UserName=?";
  $rs4=PDOQuery($dbcon,$sql,[$UserName_ipt],[PDO::PARAM_STR]);
  $UserID=$rs4[0][0]['UserID'];
  
  $SessVal=array("1",$UserID,$RealName_ipt,$Sign);    
  SetSess($SessName,$SessVal);
  
  die("1".$Sign);
}
?>