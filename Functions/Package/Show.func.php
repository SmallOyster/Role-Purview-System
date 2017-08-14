<?php

/**
* ----------------------------------------
* @name PHP公用函数库 1 内容显示函数
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 创建时间：2016-09-16
* @modify 最后修改时间：2017-06-10
* ----------------------------------------
*/


/**
* ------------------------------
* ShowCSS 设定页面css文件
* ------------------------------
* @param Arr/Str css文件路径
* @param Arr/Str css文件名
* ------------------------------
**/
function ShowCSS($Path,$FileName)
{
 //两个变量的类型检测
 if(!is_array($Path) && is_array($FileName)){die("F111");}
 if(is_array($Path) && !is_array($FileName)){die("F112");}
 
 //如果不是Array
 if(!is_array($Path) && !is_array($FileName)){
  echo "<link rel='stylesheet' href='/res/css/$Path/$FileName.css'>";
 }
 
 else if(is_array($Path) && is_array($FileName)){
  $TotalPath=sizeof($Path);
  $TotalName=sizeof($FileName);
  
  //判断两个Array元素数量是否相同
  if($TotalPath != $TotalName){
   die("F113");
  }
  
  for($i=0;$i<$TotalPath;$i++){
   echo "<link rel='stylesheet' href='/res/css/{$Path[$i]}/{$FileName[$i]}.css'>";
  }
 }
}


/**
* ------------------------------
* ShowJS 加载页面JS脚本
* ------------------------------
* @param Arr/Str JS脚本文件名
* ------------------------------
**/
function ShowJS($FileName)
{
 //如果不是Array
 if(!is_array($FileName)){
  echo "<script src='/res/js/$FileName.js'></script>";
 }
 
 else if(is_array($FileName)){
  $TotalName=sizeof($FileName);
  
  for($i=0;$i<$TotalName;$i++){
   echo "<script src='/res/js/{$FileName[$i]}.js'></script>";
  }
 }
}


/**
* ------------------------------
* makeOprBtn 显示带参数的按钮
* ------------------------------
* @param Str 显示内容
* @param Str 按钮颜色类(Bootstrap)
* @param Str 所在文件夹
* @param Str 文件名
* @param Arr 参数
* ------------------------------
**/
function makeOprBtn($name,$color,$file,$action,$param=array())
{
  $url_param="index.php?file=$file&action=$action";
  
  foreach($param as $i=>$value){
    $param_name=$param[$i][0];
    $param_value=$param[$i][1];
    $url_param.="&$param_name=$param_value";
  }
  
  $url='<a class="btn btn-'.$color.'" href="'.$url_param.'">'.$name.'</a>';
  return $url;
}


function getLetter($LetterID){
  // 首位符号是为了占位(第0个)，方便按顺序取字母
  $AllLetters="|ABCDEFGHIJKLMNOPQRSTUVWXYZ";

  if($LetterID>26){
    return "";
  }else{
    return $AllLetters[$LetterID];
  }
}


/**
* ------------------------------
* showCNNum 显示汉字的数字
* ------------------------------
* @param INT 一位数字
* ------------------------------
**/
function showCNNum($Num){
  switch($Num){
    case 1:
      $rtn="一";
      break;
    case 2:
      $rtn="二";
      break;
    case 3:
      $rtn="三";
      break;
    case 4:
      $rtn="四";
      break;
    case 5:
      $rtn="五";
      break;
    case 6:
      $rtn="六";
      break;
    case 7:
      $rtn="七";
      break;
    case 8:
      $rtn="八";
      break;
    case 9:
      $rtn="九";
      break;
    case 0:
      $rtn="零";
      break;
  }

  return $rtn;
}