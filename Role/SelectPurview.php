<?php
$RoleID=isset($_GET['RID'])?$_GET['RID']:ErrCodedie("500-GTDA");

$vfy_sql="SELECT * FROM role_list WHERE Roleid=?";
$vfy_rs=PDOQuery($dbcon,$vfy_sql,[$RoleID],[PDO::PARAM_INT]);
if($vfy_rs[1]!="1") ErrCodedie("500-NR");
$RoleName=$vfy_rs[0][0]['RoleName'];

if(isset($_POST) && $_POST){
$ids=$_POST['ids'];
$ids=explode(",",$ids);
$total=sizeof($ids);
$Insert_count=0;

//先删除该角色的所有权限
$Del_sql="DELETE FROM role_purview WHERE RoleID=?";
$Del_rs=PDOQuery($dbcon,$Del_sql,[$RoleID],[PDO::PARAM_INT]);

for($i=0;$i<$total;$i++){
  //循环添加
  $Insert_sql="INSERT INTO role_purview(RoleID,PurvID) VALUES (?,?)";
  $Insert_rs=PDOQuery($dbcon,$Insert_sql,[$RoleID,$ids[$i]],[PDO::PARAM_INT,PDO::PARAM_STR]);
  if($Insert_rs[1]>0){
    $Insert_count++;
  }
}

$rtnURL="index.php?file=Role&action=toList.php";
    
if($Insert_count>0){
  echo '<script>alert("成功给当前角色分配'.$Insert_count.'项权限！");window.location.href="'.$rtnURL.'";</script>';
}
}
?>

<script>
	var setting = {
		view: {
			selectedMulti: false
		},
		check: {
			enable: true
		},
		data: {
			simpleData: {
				enable: true
			}
		}
	};

	var zNodes=<?php include("Functions/Api/AllMenuData.php"); ?>;

	function getCheckedNodes(){
		ids="";
		var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
		nodes = zTree.getCheckedNodes();
		for (i=0,l=nodes.length;i<l;i++){
			ids+=nodes[i].id+",";
		}
		ids=ids.substr(0,ids.length-1);
		console.log(ids);
		$("#chkids").val(ids);
		$("form").submit();
	}

	$(document).ready(function(){
		$.fn.zTree.init($("#treeDemo"),setting,zNodes);
	});
</script>

<center>
  <h1>
    <?php echo "分配权限：".$RoleName; ?>
  </h1>
  <hr>
  <ul id="treeDemo" class="ztree"></ul>

  <form method="post">
    <input type="hidden" name="ids" id="chkids">
  </form>
  <hr>
  <button class="btn btn-success" style="width:80%" onclick="getCheckedNodes()">确认分配</button>
  <br><br>

</center>