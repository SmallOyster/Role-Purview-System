<?php
$nowURL="index.php?file=User&action=toList";
$list=PDOQuery($dbcon,"SELECT * FROM sys_user",[],[]);
$total=sizeof($list[0]);
$Page=isset($_GET['Page'])?$_GET['Page']:"1";
$PageSize=isset($_GET['PageSize'])?$_GET['PageSize']:"20";
$TotalPage=ceil($total/$PageSize);
$Begin=($Page-1)*$PageSize;
$Limit=$Page*$PageSize;

if($Page>$TotalPage && $TotalPage!=0){
 header("Location: $nowURL");
}

if($Limit>$total){$Limit=$total;}

if(isset($_POST) && $_POST){
  $OprType=$_POST['OprType'];
  $uid=@$_POST['uid'];
  $id=@$_POST['id'];
  if($uid!="" && $id!=""){
    $sql2="UPDATE sys_user SET ";
    if($OprType=="1"){
      if($id=="1"){
        $Pw_arr=getRanPW();
        $originPassword=$Pw_arr[0];
        $salt=$Pw_arr[1];
        $Password=$Pw_arr[2];
        $sql2.="Status=?,originPassword='{$originPassword}',salt='{$salt}',Password='{$Password}' ";
      }else{
        $sql2.="Status=?,originPassword='' ";
      }
    }else{
      $sql2.="Roleid=? ";
    }
    $sql2.="WHERE Userid=?";
    $rs2=PDOQuery($dbcon,$sql2,[$id,$uid],[PDO::PARAM_INT,PDO::PARAM_INT]);
    header("Location: $nowURL");
  }else{
    echo "<script>alert('您未选择需要修改的角色/状态\n请重试！！！');</script>";
  }
}

setSess("inUserList","1");
?>
<style>th{font-weight:bold;}</style>
<center>
  <h1>用户列表</h1><hr>
  <?php
  echo "<h3>第{$Page}页 / 共{$TotalPage}页</h3>";
  echo "<h4>共 <font color=red>{$total}</font> 个用户</h4>";
  ?>
</center>
<hr>
<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
<tr>
  <td colspan="6"><center><a href="?file=User&action=toAdd" class="btn btn-success" style="width:80%">新 增 用 户</a></center></td>
</tr>
<tr>
  <th>用户名</th>
  <th>姓名</th>
  <th>角色</th>
  <th>状态</th>
  <th>初始密码</th>
  <th>操作</th>
</tr>
<?php
  for($i=$Begin;$i<$Limit;$i++){
    $Userid=$list[0][$i]['Userid'];
    $Name=$list[0][$i]['UserName'];
    $RealName=$list[0][$i]['RealName'];
    $Roleid=$list[0][$i]['Roleid'];
    $Status=$list[0][$i]['Status'];
    $originPassword=$list[0][$i]['originPassword'];
    $oprURL='<a class="btn btn-info" href="?file=User&action=EditData&UID='.$Userid.'">编辑</a> <a class="btn btn-danger" href="?file=User&action=toDel&UID='.$Userid.'">删除</a>';
    $Roleinfo=PDOQuery($dbcon,"SELECT * FROM role_list WHERE Roleid=?",[$Roleid],[PDO::PARAM_INT]);
    $Rolename=$Roleinfo[0][0]['RoleName'];
    
    switch($Status){
     case 0:
      $Status="<a style='color:red' onclick='updateStatus($Userid)'>已禁用</a>";
      $originPassword="/";
      break;
     case 1:
      $Status="<a style='color:blue' onclick='updateStatus($Userid)'>未激活</a>";
      if($originPassword==""){
        $originPassword='<a class="btn btn-warning" href="?file=User&action=toResetPw&UID='.$Userid.'">重置密码</a>';
      }
      break;
     case 2:
      $Status="<a style='color:green' onclick='updateStatus($Userid)'>使用中</a>";
      $originPassword='<a class="btn btn-warning" href="?file=User&action=toResetPw&UID='.$Userid.'">重置密码</a>';
      break;
     default:
      $Status="<a style='color:red' onclick='updateStatus($Userid)'>异常</a>";
      $originPassword="/";
      break;
    }

?>
<tr>
  <td><?php echo $Name; ?></td>
  <td><?php echo $RealName; ?></td>
  <td><?php echo "<a onclick='getRole($Userid)'>".$Rolename."</a>"; ?></td>
  <td><?php echo $Status; ?></td>
  <td><?php echo $originPassword; ?></td>
  <td><?php echo $oprURL; ?></td>
</tr>
<?php } ?>
</table>

<center><nav>
 <ul class="pagination"> 
  <?php
  if($Page-1>0){
    $Previous=$Page-1;
  ?>
  <li>
   <a href="<?php echo $NowURL."?page=$Previous"; ?>" aria-label="Previous"> <span aria-hidden="true">&laquo;</span></a>
  </li>
  <?php } ?>
  <?php
  for($j=1;$j<=$TotalPage;$j++){
   if($j==$Page){
    echo "<li class='disabled'><a>$j</a></li>";
   }else{
    echo "<li><a href='$NowURL?page=$j'>$j</a></li>";
   }
  }
  ?>
  <?php
  if($Page+1<=$TotalPage){
    $next=$Page+1;
  ?>
  <li>
   <a href="<?php echo $NowURL."?page=$next"; ?>" aria-label="Next"> <span aria-hidden="true">&raquo;</span></a>
  </li>
  <?php } ?>
 </ul>
</nav></center>

<script>
function updateStatus(id){
 msg=''
 +'<input name="id" type="radio" value="0" onclick="updateVALUE(0)"><font color="red">已禁用</font><br>'
 +'<input name="id" type="radio" value="1" onclick="updateVALUE(1)"><font color="blue">未激活</font><br>'
 +'<input name="id" type="radio" value="2" onclick="updateVALUE(2)"><font color="green">已启用</font><br>';
 $("#OprType").val("1");
 $("#uid").val(id);
 $('#ModalTitle').html("修改用户状态");
 $('#msg').html(msg);
 $('#myModal').modal('show');
}

function updateVALUE(value){
  $("#status").val(value);
}

function getRole(id){
 msg=''
 +'<center>'
 +'<select name="id" onchange="selectRole(this.options[this.options.selectedIndex].value)">'
 +'<option selected="selected" disabled>---请选择角色---</option>'
 +'<center>';
 $.ajax({
  url:"Functions/Api/getRole.php",
  data:{type:1},
  type:"post",
  dataType:"json",
  error:function(e){alert()},
  success:function(got){
   for(i in got){
    msg+='<option ';
    for(j in got[i]){
     if(j==="id"){
      msg+='value="'+got[i][j]+'">';
     }
     else if(j==="name"){
      msg+=got[i][j]+"</option>";
     }
    }
   }
   $("#OprType").val("2");
   $("#uid").val(id);
   $('#ModalTitle').html("修改用户角色");
   $('#msg').html(msg);
   $('#myModal').modal('show');
  }
 });
}

function selectRole(rid){
 detail="";
 $.ajax({
  url:"Functions/Api/getRole.php",
  data:{type:2,rid:rid},
  type:"post",
  dataType:"json",
  error:function(e){alert(JSON.stringify(e))},
  success:function(got){
   detail+='<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;"><h4><tr>';
   for(i in got[0]){
    if(i==="name"){
     detail+="<th>角色名称</th><td><font color='green'>"+got[0][i]+"</font></td></tr>";
    }else{
     detail+="<th>角色简介</th><td><font color='blue'>"+got[0][i]+"</font></td></tr></table>";
    }
   }
   $('#detail').html(detail);
   //$('#myModal').modal('show');
  }
 });
}

function submitForm(){
  type=$("#OprType").val();
  status=$("#status").val();
  if(type=="1"){
    if(status=="0"){
      if(confirm("确定要禁用此用户吗？")){
        $("form").submit();
      }
    }else if(status=="1"){
      if(confirm("确定要重置此用户状态吗？\n这将导致其密码初始化！")){
        $("form").submit();
      }
    }else{
      $("form").submit();
    }
  }else{
    $("form").submit();
  }
}
</script>

<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="ModalTitle">温馨提示</h3>
      </div>
      <div class="modal-body">
        <div style="overflow:hidden;">
        </div>
        <form method="post">
        <input type="hidden" id="uid" name="uid">
        <input type="hidden" id="OprType" name="OprType">
        <input type="hidden" id="status">
        <p id="msg"></p><hr>
        <p id="detail"></p>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">&lt; 取消</button>
        <button type="button" class="btn btn-success" id='okbtn' onclick='submitForm()'>确定 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->