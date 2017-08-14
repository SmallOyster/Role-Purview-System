<?php

/**
* -----------------------------------------
* @name PHP公用函数库 5 缓存类
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 创建时间：2017-04-09
* @modify 最后修改时间：2017-06-27
* -----------------------------------------
*/

class Cache{

  public $dbcon;// 数据库连接对象
  public $TableName;// 缓存在数据库的表名
  
  function __construct($dbcon,$Suffix){
    $this->dbcon=$dbcon;
    $this->TableName="cache_".$Suffix;
  }
  

  /**
  * -------------------------------------
  * S 设置缓存
  * -------------------------------------
  * @param Array 缓存的列名
  * @param Array 每列对应的值
  * -------------------------------------
  */
  function S($Key,$Value)
  {
    $Key_Total=count($Key);
    $Value_Total=count($Value);
    $sql="INSERT INTO ".$this->TableName." SET ";
    
    if($Key_Total!=$Value_Total){
      toAlertDie("C-CC-1-TT");
    }
    
    for($i=0;$i<$Key_Total;$i++){
      $Key_Now=$Key[$i];
      $Value_Now=$Value[$i];
      $sql.=$Key_Now."='".$Value_Now."',";
    }
    
    $sql=substr($sql,0,strlen($sql)-1);
    $rs=PDOQuery($this->dbcon,$sql,[],[]);
    
    return $rs;
  }
  

  /**
  * -------------------------------------
  * G 获取缓存
  * -------------------------------------
  * @param Array 条件(列名->内容)
  * -------------------------------------
  */
  function G($condition)
  {  
    $sql="SELECT * FROM ".$this->TableName." WHERE ";
    
    if(is_array($condition)==false){
      toAlertDie("C-CC-2-INA");
    }
    
    foreach($condition as $Value){
      $sql.=$Value[0]."='".$Value[1]."' AND ";
    }
    
    $sql=substr($sql,0,strlen($sql)-5);
    $rs=PDOQuery($this->dbcon,$sql,[],[]);
    
    return $rs;
  }
  
  
  /**
  * -------------------------------------
  * E 清空已过期的缓存
  * -------------------------------------
  */
  function E()
  {
    $time=time();
    $sql="DELETE FROM ".$this->TableName." WHERE ExpTime<$time";
    $rs=PDOQuery($this->dbcon,$sql,[],[]);
    return $rs;
  }


  /**
  * -------------------------------------
  * D 删除指定缓存
  * -------------------------------------
  * @param String SessionID(可空)
  * @param String 用户ID
  * -------------------------------------
  */
  function D($SessionID,$UserID)
  {
    if($SessionID==""){
      $sql="DELETE FROM ".$this->TableName." WHERE UserID=?";
      $rs=PDOQuery($this->dbcon,$sql,[$UserID],[PDO::PARAM_STR]);
    }else{
      $sql="DELETE FROM ".$this->TableName." WHERE SessionID=? AND UserID=?";
      $rs=PDOQuery($this->dbcon,$sql,[$SessionID,$UserID],[PDO::PARAM_STR,PDO::PARAM_STR]);
    }
    
    return $rs;
  }
}
