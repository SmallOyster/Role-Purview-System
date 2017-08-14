<?php
$list=PDOQuery($dbcon,"SELECT * FROM sys_log",[],[]);
$total=sizeof($list[0]);

// 分页代码[Begin]
$Page=isset($_GET['Page'])?$_GET['Page']:"1";
$PageSize=isset($_GET['PageSize'])?$_GET['PageSize']:"20";
// 总共的页数
$TotalPage=ceil($total/$PageSize);
if($TotalPage==0){$TotalPage=1;}
// 计算偏移量
$offset=$PageSize*($Page-1);
$Begin=($Page-1)*$PageSize;
$Limit=$Page*$PageSize;

if($Page>$TotalPage && $TotalPage!=0){
  die("<script>window.location.href='$nowURL';</script>");
}

if($Limit>$total){$Limit=$total;}
// 分页代码[End]

?>

<center>
  <h1>操作记录</h1><hr>
  <?php
  echo "<h2>第{$Page}页 / 共{$TotalPage}页</h2>";
  echo "<h3>共 <font color=red>{$total}</font> 条</h3>";
  ?>
</center>
<hr>

<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">

<tr>
  <td colspan="5">
    <a class="btn btn-danger" href="index.php?file=Sys&action=toEmptyLog.php" style="width:98%">清 空 操 作 记 录</a>
  </td>
</tr>

<tr>
  <th>操作类型</th>
  <th>操作内容</th>
  <th>操作人</th>
  <th>操作时间</th>
  <th>操作IP</th>
</tr>

<?php
for($i=$Begin;$i<$Limit;$i++){
  $LogID=$list[0][$i]['LogID'];
  $LogType=$list[0][$i]['LogType'];
  $LogContent=$list[0][$i]['LogContent'];
  $LogTime=$list[0][$i]['LogTime']; 
  $LogIP=$list[0][$i]['LogIP'];
  $LogUser=$list[0][$i]['LogUser'];
?>

<tr>
  <td><?php echo $LogType; ?></td>
  <td><?php echo $LogContent; ?></td>
  <td><?php echo $LogUser; ?></td>
  <td><?php echo $LogTime; ?></td>
  <td><?php echo $LogIP; ?></td>
</tr>
<?php } ?>
</table>

<hr>

<!-- 分页功能@选择页码[Begin] -->
<center><nav>
 <ul class="pagination"> 
  <?php
  if($Page-1>0){
    $Previous=$Page-1;
  ?>
  <li>
   <a href="<?php echo $nowURL."&Page=$Previous"; ?>" aria-label="Previous"> <span aria-hidden="true">&laquo;</span></a>
  </li>
  <?php } ?>
  <?php
  for($j=1;$j<=$TotalPage;$j++){
   if($j==$Page){
    echo "<li class='active'><span>$j</span></li>";
   }else{
    echo "<li><a href='$nowURL&Page=$j'>$j</a></li>";
   }
  }
  ?>
  <?php
  if($Page+1<=$TotalPage){
    $next=$Page+1;
  ?>
  <li>
   <a href="<?php echo $nowURL."&Page=$next"; ?>" aria-label="Next"> <span aria-hidden="true">&raquo;</span></a>
  </li>
  <?php } ?>
 </ul>
</nav></center>
