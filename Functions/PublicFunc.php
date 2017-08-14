<?php
/**
* ----------------------------------------
* @name 小生蚝角色权限系统 PHP公用函数库
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 创建时间：2016-09-16
* @modify 最后修改时间：2017-06-10
* ----------------------------------------
*/

/* Base Settings */
SESSION_START();
date_default_timezone_set('Asia/Shanghai');


/* Require Package <Showing> */
require_once("Package/Show.func.php");
/* Require Package <Session> */
require_once("Package/Session.func.php");
/* Require Package <User Privacy> */
require_once("Package/Privacy.func.php");
/* Require Class <Global Settings> */
require_once("Package/Settings.class.php");
/* Require Class <Cache> */
require_once("Package/Cache.class.php");



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
    // Ajax处理页（直接返回错误内容文字）
    $Alerting=$ErrorNo."\n".$Tips;
  }else if($isInScript==0 || $isInScript==""){
    // PHP普通页面（script标签+alert）
    $Alerting='<script>alert("Oops！系统处理出错了！\n\n错误码：'.$ErrorNo.'\n'.$Tips.'");history.go(-1);</script>';
  }else if($isInScript==1){
    // JS代码内（直接alert）
    $Alerting='alert("Oops！系统处理出错了！\n\n错误码：'.$ErrorNo.'\n'.$Tips.'");';
  } 

  die($Alerting);
}


/**
* ------------------------------
* ErrCodedie 页面仅显示错误码并die
* ------------------------------
* @param String 自定义错误码
* ------------------------------
**/
function ErrCodedie($ErrorCode)
{
  die('<center><h1>'.$ErrorCode.'</h1><hr><p style="font-weight:bolder;font-size:18;line-height:23px;">&copy; 生蚝科技 2014-2017</p></center>');
}


/**
* ------------------------------
* TextFilter 过滤字符（留下数字,字母,某些符号）
* ------------------------------
* @param String 需要过滤的字符串
* ------------------------------
* @return String 过滤后的字符串
* ------------------------------
**/
function TextFilter($str)
{
  $Letters="qwertyuiopasdfghjklzxcvbnm";
  $Numbers="1234567890";
  
  $all=$Letters.$Numbers.".";
  $length=strlen($str);
  
  for($i=0;$i<$length;$i++){
    // 检测是否为汉字
    if(preg_match("/([\x81-\xfe][\x40-\xfe])/",$str,$match)){
      continue;
    }
    
    // 检测是否在允许范围内   
    if(stripos($all,$str[$i])===false){
      $str[$i]="";
    }
  }
  
  return $str;
}


/**
* ------------------------------
* getIP 获取客户端IP地址
* ------------------------------
* @return String 客户端IP地址
* ------------------------------
**/
function getIP()
{
  if(!empty($_SERVER["HTTP_CLIENT_IP"])){
    $cip = $_SERVER["HTTP_CLIENT_IP"];
  }elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
    $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
  }elseif(!empty($_SERVER["REMOTE_ADDR"])){
    $cip = $_SERVER["REMOTE_ADDR"];
  }else{
    $cip = "无法获取！";
  }
  
  return $cip;
}
