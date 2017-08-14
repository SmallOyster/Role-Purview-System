<?php
require_once("../PublicFunc.php");
$Sets=new Settings("../../GlobalSettings.json");
$Notice=$Sets->G("Notice_Content",2,"Global");
$PubTime=$Sets->G("Notice_PubTime",2,"Global");

$rtn["Content"]=$Notice;
$rtn["PubTime"]=$PubTime;
die(json_encode($rtn));

?>