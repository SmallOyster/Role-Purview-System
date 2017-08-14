<?php

/**
* -----------------------------------------
* @name PHP公用函数库 2 Session操作函数
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 创建时间：2016-08-28
* @modify 最后修改时间：2016-10-06
* -----------------------------------------
*/

/**
* -------------------------------------
* SetSess 设置Session
* -------------------------------------
* @param Arr(一维)/Str Session名称
* @param Arr(一维)/Str 对应Session值
* -------------------------------------
**/
function SetSess($SessName,$SessValue)
{
 if(!is_array($SessName) && !is_array($SessValue)){
 	$_SESSION[$SessName]=$SessValue;
 }else{
  $TotalName=sizeof($SessName);
  $TotalValue=sizeof($SessValue);
  if($TotalName != $TotalValue){
   toAlertDie("211");
  }
  for($i=0;$i<$TotalName;$i++){
   $_SESSION[$SessName[$i]]=$SessValue[$i];
  }
 }
}


/**
* -------------------------------------
* GetSess 获取Session值
* -------------------------------------
* @param String 要取值的Session名
* -------------------------------------
* @return Arr/Str 对应Session值
* -------------------------------------
**/
function GetSess($SessName)
{
 if(!@$_SESSION[$SessName]) return null;
 else return $_SESSION[$SessName];
}