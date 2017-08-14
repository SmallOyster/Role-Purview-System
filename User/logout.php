<?php
require_once("../Functions/PDOConn.php");
require_once("../Functions/PublicFunc.php");

$GB_Sets=new Settings("../GlobalSettings.json");
define("Prefix",$GB_Sets->G("SessionPrefix",2,"System"));

$SessionID=session_ID();
$UserID=getSess(Prefix."UserID");

$Cache=new Cache($dbcon,"login");
$Cache->D("",$UserID);

$url="Login.php";

$re_Param=isset($_GET['re_Param'])?$_GET['re_Param']:"";
if($re_Param!=""){
  $url=$url."?re=1&re_Param=".$re_Param;
}

session_destroy();

header("Location: $url");
?>