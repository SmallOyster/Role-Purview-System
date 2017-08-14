<?php
class OEA{
  public function Encrypt($ctx){
    $Str="%QWERTY24UPASD*FGH57JKZ,XCVBNM89$";
    $Key="";
  
    for($i=0;$i<8;$i++){
      $StrLen=strlen($Str);
      $Loc=mt_rand(0,$StrLen-1);
      $randStr=$Str[$Loc];
      $Key.=$randStr;
      $Str=str_replace($randStr,"",$Str);
    }
  
    $ctx=base64_encode($ctx);
    $ctxLen=strlen($ctx);
    $ctxLen1=floor($ctxLen/5);
    $ctx1=substr($ctx,0,$ctxLen1);
    $ctx2=substr($ctx,$ctxLen1);
  
    $ctx=$ctx2.$Key.$ctx1;
  
    $rtn=base64_encode($ctx);
    $rtn=str_replace("==","",$rtn);
   
    return array($rtn,$Key);
  }
  
  public function Decipher($ctx,$Key){
    $ctx=base64_decode($ctx);
    $Loc=strpos($ctx,$Key);
    $ctx1=substr($ctx,0,$Loc);
    $ctx2=substr($ctx,$Loc+strlen($Key));
    $ctx3=$ctx2.$ctx1;
    return $ctx3=base64_decode($ctx3);
  }
}
