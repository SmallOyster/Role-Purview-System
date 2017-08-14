<?php
$OEA=new OEA();

$u=isset($_GET['u'])?$_GET['u']:"";
$r=isset($_GET['r'])?$_GET['r']:"";
$p=isset($_GET['p'])?$_GET['p']:"";
$k=isset($_GET['k'])?$_GET['k']:"";
$p2=$OEA->Decipher($p,$k);

$f=isset($_GET['re_file'])?$_GET['re_file']:"";
$a=isset($_GET['re_action'])?$_GET['re_action']:"";

if(!$u || !$r || !$p || !$k || $p2==""){
  echo '<script>alert("参数错误！\n请从正确的途径进入页面！");window.location.href="index.php";</script>';
}

?>

<h1>用户初始密码</h1>
<hr>
<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
  <tr>
    <th>用户名</th>
    <td><font color="green"><?php echo $u; ?></font></td>
  </tr>
  <tr>
   <th>姓名</th>
   <td><font color="green"><?php echo $r; ?></font></td>
  </tr>   
  <tr>
    <th>初始密码</th>
    <td><font color="red"><?php echo $p2; ?></font></td>
  </tr>
</table>

<center>
  <?php if($f && $a){ ?>
    <a class="btn btn-primary" href="index.php?file=<?php echo $f; ?>&action=<?php echo $a; ?>">&lt; 返回，继续操作</a>
  <?php } ?>
  <a class="btn btn-success" href="index.php">&gt; 确定，去主页面</a>
</center>