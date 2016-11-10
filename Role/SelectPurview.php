<?php
if(isset($_POST) && $_POST){
$ids=$_POST['ids'];
$ids=explode(",",$ids);
$roleid=1;

$Delsql="DELETE FROM role_purview WHERE Roleid=?";
$Delrs=PDOQuery($dbcon,$Delsql,[$roleid],[PDO::PARAM_INT]);

for($i=0;$i<sizeof($ids);$i++){
  $Insertsql="INSERT INTO role_purview(Roleid,Purvid) VALUES (?,?)";
  $Insertrs=PDOQuery($dbcon,$Insertsql,[$roleid,$ids[$i]],[PDO::PARAM_INT,PDO::PARAM_STR]);
}
}
?>

<script type="text/javascript" src="res/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="res/js/jquery.ztree.core.js"></script>
<script type="text/javascript" src="res/js/jquery.ztree.excheck.js"></script>
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

	var zNodes=<?php include("Functions/AllMenuData.php"); ?>;

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

<h1>checkbox 勾选统计</h1>
<div class="content_wrap">
	<div class="zTreeDemoBackground left">
		<ul id="treeDemo" class="ztree"></ul>
	</div>
</div>
<form method="post">
 <input type="hidden" name="ids" id="chkids">
</form>
<button onclick="getCheckedNodes()">确认分配</button>