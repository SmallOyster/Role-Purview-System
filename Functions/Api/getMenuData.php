<?php
include("../PDOConn.php");
if(isset($_POST) && $_POST){
$MenuID=$_POST['MID'];
$rs=PDOQuery($dbcon,"SELECT * FROM sys_menu WHERE MenuID=?",[$MenuID],[PDO::PARAM_INT]);
$MenuData_arr=array();

$MenuData_arr[0]['MenuID']=$MenuID;
$MenuData_arr[0]['FatherID']=$rs[0][0]['FatherID'];
$MenuData_arr[0]['Menuname']=$rs[0][0]['Menuname'];
$MenuData_arr[0]['MenuIcon']=$rs[0][0]['MenuIcon'];
$MenuData_arr[0]['PageFile']=$rs[0][0]['PageFile'];
$MenuData_arr[0]['PageDOS']=$rs[0][0]['PageDOS'];

echo urldecode(json_encode($MenuData_arr));
}
?>