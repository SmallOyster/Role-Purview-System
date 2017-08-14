<?php
$MyUserID=GetSess(Prefix."UserID");
$list=PDOQuery($dbcon,"SELECT * FROM sys_user",[],[]);
$total=sizeof($list[0]);

// 分页代码[Begin]
$Page=isset($_GET['Page'])?$_GET['Page']:"1";
$PageSize=isset($_GET['PageSize'])?$_GET['PageSize']:"20";
$TotalPage=ceil($total/$PageSize);
$Begin=($Page-1)*$PageSize;
$Limit=$Page*$PageSize;

if($Page>$TotalPage && $TotalPage!=0){
 header("Location: $nowURL");
}

if($Limit>$total){$Limit=$total;}
// 分页代码[End]

if(isset($_POST) && $_POST){
  $OprType=$_POST['OprType'];
  // 用户ID
  $uID=@$_POST['uID'];
  // 角色/状态ID
  $ID=@$_POST['ID'];
  if($uID!="" && $ID!=""){
    $sql2="UPDATE sys_user SET ";
    if($OprType=="1"){
      // 如果要重置用户激活信息
      if($ID=="1"){
        $Pw_arr=getRanPW();
        $originPassword=$Pw_arr[0];
        $salt=$Pw_arr[1];
        $Password=$Pw_arr[2];
        $sql2.="Status=?,originPassword='{$originPassword}',salt='{$salt}',Password='{$Password}' ";
      }else{
        // 修改状态并清空初始密码
        $sql2.="Status=?,originPassword='' ";
      }
    }else{
      $sql2.="RoleID=? ";
    }
    $sql2.="WHERE UserID=?";
    
    $rs2=PDOQuery($dbcon,$sql2,[$ID,$uID],[PDO::PARAM_INT,PDO::PARAM_INT]);
    die("<script>window.location.href='$nowURL';</script>");
  }else{
    echo "<script>alert('您未选择需要修改的角色/状态\n请重试！！！');</script>";
  }
}

//给重置密码验证是否直接输URL进行重置
setSess(Prefix."inUserList","1");
?>

<center>
  <h1>用户列表</h1><hr>
  <?php
  echo "<h2>第{$Page}页 / 共{$TotalPage}页</h2>";
  echo "<h3>共 <font color=red>{$total}</font> 个用户</h3>";
  ?>
</center>
<hr>
<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
<tr>
  <td colspan="6"><center><a href="?file=User&action=AddUser.php" class="btn btn-success" style="wIDth:80%">新 增 用 户</a></center></td>
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
    $UserID=$list[0][$i]['UserID'];
    $UserName=$list[0][$i]['UserName'];
    $RealName=$list[0][$i]['RealName'];
    $RoleID=$list[0][$i]['RoleID'];
    $Status=$list[0][$i]['Status'];
    $originPassword=$list[0][$i]['originPassword'];
    if($UserID!=$MyUserID){
      $oprURL=makeOprBtn("编辑","info","User","EditProfile.php",[["UID",$UserID],["UserName",$UserName],["RealName",$RealName]]);
      $oprURL.=makeOprBtn("删除","danger","User","toDelUser.php",[["UID",$UserID]]);
    }else{
      $oprURL="";
    }
    $Roleinfo=PDOQuery($dbcon,"SELECT * FROM role_list WHERE RoleID=?",[$RoleID],[PDO::PARAM_INT]);
    $Rolename=@$Roleinfo[0][0]['RoleName'];
    if($Rolename==""){
      $Rolename="<font color='red'>无角色用户</font>";
    }
    
    // 根据用户状态判断它能否重置密码
    switch($Status){
     //禁用，不能重置密码
     case 0:
      $Status="<a style='color:red' onclick='updateStatus($UserID)'>已禁用</a>";
      $originPassword="<center>/</center>";
      break;
     //未激活，显示初始密码
     case 1:
      $Status="<a style='color:blue' onclick='updateStatus($UserID)'>未激活</a>";
      break;
     //启用，可以重置密码
     case 2:
      $Status="<a style='color:green' onclick='updateStatus($UserID)'>使用中</a>";
      //如果不是现在这个用户，可以重置
      if($UserID!=$MyUserID){
        $originPassword='<a class="btn btn-warning" href="?file=User&action=toResetPW.php&UID='.$UserID.'&n='.$UserName.'&r='.$RealName.'">重置密码</a>';
      }else{
        $originPassword="";
      }
      break;
     default:
      $Status="<a style='color:red' onclick='updateStatus($UserID)'>异常</a>";
      $originPassword="/";
      break;
    }

?>
<tr>
  <td><?php echo $UserName; ?></td>
  <td><?php echo $RealName; ?></td>
  <td><?php echo "<a onclick='getRole($UserID)'>".$Rolename."</a>"; ?></td>
  <td><?php echo $Status; ?></td>
  <td><?php echo $originPassword; ?></td>
  <td><?php echo $oprURL; ?></td>
</tr>
<?php } ?>
</table>

<!-- 分页功能@选择页码[Begin] -->
<center><nav>
 <ul class="pagination"> 
  <?php
  if($Page-1>0){
    $Previous=$Page-1;
  ?>
  <li>
   <a href="<?php echo $nowURL."&Page=$Previous"; ?>" aria-label="Previous"> <span aria-hIDden="true">&laquo;</span></a>
  </li>
  <?php } ?>
  <?php
  for($j=1;$j<=$TotalPage;$j++){
   if($j==$Page){
    echo "<li class='disabled'><a>$j</a></li>";
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
   <a href="<?php echo $nowURL."&Page=$next"; ?>" aria-label="Next"> <span aria-hIDden="true">&raquo;</span></a>
  </li>
  <?php } ?>
 </ul>
</nav></center>
<!-- 分页功能@选择页码[End] -->


<script>
function updateStatus(ID){
 msg='';
 msg='<input name="ID" type="radio" value="0" onclick="updateVALUE(0)"><font color="red">已禁用</font><br>'
 +'<input name="ID" type="radio" value="1" onclick="updateVALUE(1)"><font color="blue">未激活</font><br>'
 +'<input name="ID" type="radio" value="2" onclick="updateVALUE(2)"><font color="green">已启用</font><br>';
 $("#OprType").val("1");
 $("#uID").val(ID);
 $('#ModalTitle').html("修改用户状态");
 $('#msg').html(msg);
 $('#detail').html("");
 $('#myModal').modal('show');
}

function updateVALUE(value){
  $("#status").val(value);
}

function getRole(ID){
 msg='';
 msg='<center>'
 +'<select name="ID" onchange="selectRole(this.options[this.options.selectedIndex].value)">'
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
     if(j==="ID"){
      msg+='value="'+got[i][j]+'">';
     }
     else if(j==="name"){
      msg+=got[i][j]+"</option>";
     }
    }
   }
   $("#OprType").val("2");
   $("#uID").val(ID);
   $('#ModalTitle').html("修改用户角色");
   $('#msg').html(msg);
   $('#myModal').modal('show');
  }
 });
}

function selectRole(rID){
 detail="";
 $.ajax({
  url:"Functions/Api/getRole.php",
  data:{type:2,rID:rID},
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
  }
 });
}

function submitForm(){
  type=$("#OprType").val();
  status=$("#status").val();
  if(type=="1"){
    if(status=="0"){
      //如果要禁用用户
      if(confirm("确定要禁用此用户吗？")){
        $("form").submit();
      }
    }else if(status=="1"){
      //如果要重置用户激活信息
      if(confirm("确定要重置此用户的激活信息吗？\n这将导致其密码初始化！")){
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

<div class="modal fade" ID="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hIDden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" ID="ModalTitle">温馨提示</h3>
      </div>
      <div class="modal-body">
        <div style="overflow:hIDden;">
        </div>
        <form method="post">
        <input type="hIDden" ID="uID" name="uID">
        <input type="hIDden" ID="OprType" name="OprType">
        <input type="hIDden" ID="status">
        <p ID="msg"></p><hr>
        <p ID="detail"></p>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">&lt; 取消</button>
        <button type="button" class="btn btn-success" ID='okbtn' onclick='submitForm()'>确定 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->