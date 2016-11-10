<?php
$rs=PDOQuery($dbcon,"SELECT * FROM sys_menu",[],[]);
echo $rs[1]+1;
?>