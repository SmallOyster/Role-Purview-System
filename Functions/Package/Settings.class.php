<?php
/**
* -----------------------------------------
* @name PHP公用函数库 4 网站全局设置项配置
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 创建时间：2016-12-03
* @modify 最后修改时间：2017-08-12
* -----------------------------------------
*/


class Settings{
  
  //全局设置的字符串
  public $Settings;
  
  function __construct($FileName){
    $this->FileName=$FileName;
    $this->Settings=file_get_contents($this->FileName);
    $this->Settings=(array)json_decode($this->Settings,true);
  }
  
  
  /**
  * function G 获取全局设置项的内容
  * @param String 设置项名称
  * @param 1|2 全局设置项所在数组维度
  * @param String 所在二维数组的第一维名称
  */
  function G($Name,$Dimension=1,$FirstName="")
  {
    if($Dimension==2){
      if(!isset($this->Settings[$FirstName][$Name])){
        return "";
      }else{
        return $this->Settings[$FirstName][$Name];
      }
    }else{
      if(!isset($this->Settings[$Name])){
        return "";
      }else{
        return $this->Settings[$Name];
      }
    }
  }
  
  /**
  * function S 设置全局设置项的内容
  * @param String 内容
  * @param String 设置项名称
  * @param 1|2 全局设置项所在数组维度
  * @param String 所在二维数组的第一维名称
  */
  function S($Value,$Name,$Dimension=1,$FirstName="")
  {
    $Settings_json="";
    if(is_writable($this->FileName)){
      //根据设置项所在维度分开处理
      if($Dimension==2){
        //存在于二维
        $this->Settings[$FirstName][$Name]=$Value;
          $Settings_json=(string)json_encode($this->Settings,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        file_put_contents($this->FileName, $Settings_json);
        return "1";
      }else{
        //存在于一维
        $this->Settings[$Name]=$Value;
          $Settings_json=(string)json_encode($this->Settings,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        file_put_contents($this->FileName, $Settings_json);
        return "1";
      }
    }else{
      toAlertDie("F4203");
    }
    
    $this->Settings=file_get_contents($this->FileName);
    $this->Settings=(array)json_decode($this->Settings,true);
  }
}
