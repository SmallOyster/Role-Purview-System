<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="res/css/font-awesome.min.css" rel="stylesheet">
	<link href="res/css/bootstrap.min.css" rel="stylesheet">
	<script type="text/javascript" src="http://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>

	<link rel="stylesheet" href="res/css/demo.css" type="text/css">
	<link rel="stylesheet" href="res/css/zTreeStyle/zTreeStyle.css" type="text/css">
	<script type="text/javascript" src="res/js/jquery.ztree.core.js"></script>
	<script type="text/javascript" src="res/js/jquery.ztree.excheck.js"></script>
	<script type="text/javascript" src="res/js/jquery.ztree.exedit.js"></script>
	<script src="res/js/bootstrap.min.js"></script>
</head>

<body>
<?php
require_once("Functions/PDOConn.php");
require_once("Functions/PublicFunc.php");

// 正式启用时请删除！！！
SetSess(array("Roleid","RoleName"),array("1","超级管理员"));
// 正式启用时请删除！！！


//待加载页面的参数 + 参数过滤
$file=@$_GET['file']==''?'View':$_GET['file'];
$action=@$_GET['action']==''?'index':$_GET['action'];
$file=TextFilter($file);
$action=TextFilter($action);

//获取当前页面的ID
$Menuid_rs=PDOQuery($dbcon,"SELECT * FROM sys_menu WHERE PageFile=? AND PageDOS=?",[$file,$action],[PDO::PARAM_STR,PDO::PARAM_STR]);
$Menuid=@$Menuid_rs[0][0]['Menuid'];

//所有允许权限的Array Session
$AllPurv=GetSess("AllPurv");

if($AllPurv==null){
  //获取当前角色的所有权限ID
 	$Roleid=GetSess("Roleid");
 	$AllPurv_rs=PDOQuery($dbcon,"SELECT * FROM role_purview WHERE Roleid=?",[$Roleid],[PDO::PARAM_INT]);
	$TotalPurv=sizeof($AllPurv_rs[0]);
	
	//循环添加给所有允许权限的Array Session
	for($i=0;$i<$TotalPurv;$i++){
	  $_SESSION['AllPurv'][$i]=$AllPurv_rs[0][$i]['Purvid'];
	}
}else{
/*  if(!in_array($Menuid,$_SESSION['AllPurv'])){
    echo "<script>alert('No Purview');</script>";
  }else{
    echo "<script>alert('有权限');</script>";
  }*/
}
include("Functions/ShowNav.php");
//$filename=$file.'/'.$action.'.php';
include($file.'/'.$action.'.php');
/*if($inc==false){
 echo $file.'/'.$action.'.php';
}*/
?>