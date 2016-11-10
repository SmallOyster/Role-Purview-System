<?php
/**
* @name 小生蚝角色权限系统 PHP公用函数库
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 创建时间：2016-09-16
* @modify 最后修改时间：2016-10-06
*/

/* Base Settings */
SESSION_START();
$NowURL=$_SERVER['PHP_SELF'];
$NowURL=substr($NowURL,strrpos($NowURL,'/')+1);

/* Require Package <Showing> */
require_once("Package/Show.func.php");
/* Require Package <Session> */
require_once("Package/Session.func.php");
/* Require Package <User Privacy> */
require_once("Package/Privacy.func.php");



/**
* ------------------------------
* toAlertDie 弹框并die
* ------------------------------
* @param String 自定义错误码
* @param String 可选，自定义提示内容
* ------------------------------
**/
function toAlertDie($ErrorNo,$Tips="",$isInScript="")
{
 if($isInScript=="Ajax"){
  $Alerting=$ErrorNo."\n".$Tips;
  $ErrorNo="";
 }else if($isInScript==0 || $isInScript==""){
 $Alerting='<script>alert("Oops！系统处理出错了！\n\n错误码：'.$ErrorNo.'\n'.$Tips.'");</script>';
 }else if($isInScript==1){
  $Alerting='alert("Oops！系统处理出错了！\n\n错误码：'.$ErrorNo.'\n'.$Tips.'");';
 } 
 die($Alerting.$ErrorNo);
}


function TextFilter($str){
	return preg_replace('/[^0-9a-zA-Z]+/','',$str);
}
?>