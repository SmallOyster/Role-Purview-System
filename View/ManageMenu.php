<?php
if(isset($_POST) && $_POST){
var_dump($_POST);
/*
$rs=PDOQuery($dbcon,"SELECT * FROM sys_menu WHERE Menuid=?",[$Menuid],[PDO::PARAM_STR]);
*/
$OprType=!isset($_POST['OprType'])?"0":$_POST['OprType'];

switch($OprType){
 //编辑
 case 1:
  $id=$_POST['id'];
  $name=$_POST['Name'];
  $file=$_POST['File'];
  $DOS=$_POST['DOS'];
  $icon=$_POST['Icon'];
  $sql="UPDATE sys_menu SET Menuname=?,PageFile=?,PageDOS=?,MenuIcon=? WHERE Menuid=?";
  $rs=PDOQuery($dbcon,$sql,[$name,$file,$DOS,$icon,$id],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_INT]);
  break;
 //新增
 case 2:
  $fid=$_POST['FID'];
  $name=$_POST['Name'];
  $file=$_POST['File'];
  $DOS=$_POST['DOS'];
  $icon=$_POST['Icon'];
  $sql="INSERT INTO sys_menu(Fatherid,Menuname,MenuIcon,PageFile,PageDOS) VALUES(?,?,?,?,?)";
  $rs=PDOQuery($dbcon,$sql,[$name,$file,$DOS,$icon,$fid],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_INT]);
  break;
 //删除
 case 3:
  $id=$_POST['id'];
  $sql="DELETE FROM sys_menu WHERE Menuid=?";
  $rs=PDOQuery($dbcon,$sql,[$id],[PDO::PARAM_INT]);
  break;
 //空
 case 0:
  break;
 default:
  break;
}
}
?>

<script type="text/javascript" src="cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
<script type="text/javascript" src="res/js/jquery.ztree.core.js"></script>
<script type="text/javascript" src="res/js/jquery.ztree.excheck.js"></script>
<script type="text/javascript" src="res/js/jquery.ztree.exedit.js"></script>
<script>
 var setting = {
  view: {
   addHoverDom: addHoverDom,
   removeHoverDom: removeHoverDom,
   selectedMulti: false
  },
  edit: {
   enable: true,
   editNameSelectAll: false
  },
  data: {
   simpleData: {
    enable: true
   }
  },
  callback: {
   beforeEditName: beforeEditName,
   beforeRemove: beforeRemove
  }
 };
 
 var zNodes = <?php include("Functions/AllMenuData.php"); ?>;
 $(document).ready(function(){
  $.fn.zTree.init($("#treeDemo"), setting, zNodes);
 });
	
 //点击编辑按钮后
 function beforeEditName(treeId,treeNode){
  var zTree = $.fn.zTree.getZTreeObj("treeDemo");
  zTree.selectNode(treeNode);
  setTimeout(function(){
setModalMsg(1,treeNode.id,treeNode.name);
   $('#myModal').modal('show');
  }, 0);
  return false;
 }
		
 //点击删除按钮后
 function beforeRemove(treeId,treeNode){
  var zTree = $.fn.zTree.getZTreeObj("treeDemo");
  zTree.selectNode(treeNode);
  setModalMsg(8,treeNode.id,treeNode.name);
  $('#myModal').modal('show');
  return false; 
 }
 
	//点击新增按钮后	
 function addHoverDom(treeId,treeNode){
  var zTree = $.fn.zTree.getZTreeObj("treeDemo");
  var sObj = $("#" + treeNode.tId + "_span");
  if(treeNode.editNameFlag || $("#addBtn_"+treeNode.tId).length>0) return;
  var addStr = "<span class='button add' id='addBtn_" +treeNode.tId+ "' title='add node' onfocus='this.blur();'></span>";
  sObj.after(addStr);
  var btn = $("#addBtn_"+treeNode.tId);
  if(btn) btn.bind("click", function(){   
   // 新增节点
   setModalMsg(2,treeNode.id,treeNode.name);
   $('#myModal').modal('show');
   return false;
  });		
 }
 
 //当节点失去焦点，移除节点按钮组
 function removeHoverDom(treeId,treeNode){
  $("#addBtn_"+treeNode.tId).unbind().remove();
 }


 /**
 * ----------------
 *   Form表单参数
 * ----------------
 * id 当前节点ID
 * FID 父节点ID
 * Name 节点名称
 * File 节点指向目录
 * DOS 节点指向文件
 * Icon 节点图标名称，详见font-awesome类
 */
 function setModalMsg(type,id="",name=""){
  switch(type){
   /*********** 修改 Edit ***********/
   case 1:
    $("#OprType").val(1);
    $('#ModalTitle').html("修改节点信息");
    msg="<br>"
    +"<input type='hidden' name='id' value='"+id+"'>"
    +"节点名称：<input name='Name' value='"+name+"'><br>"
    +"对应目录：<input name='File'><br>"
    +"对应文件：<input name='DOS'><br>"
    +"图标名称：<input name='Icon'>";
    $('#msg').html(msg);
    $("#okbtn").attr("onclick","submitOpr();");
    break;
   /*********** 新增 Add ***********/
   case 2:
    $("#OprType").val(2);
    $('#ModalTitle').html("新增节点");
    msg=''
    +"<input type='hidden' name='FID' value='"+id+"'>"
    +"父节点名称："+id+". "+name+"<hr>"
    +"节点名称：<input name='Name'><br>"
    +"对应目录：<input name='File'><br>"
    +"对应文件：<input name='DOS'><br>"
    +"图标名称：<input name='Icon'>";
    $('#msg').html(msg);
    $("#okbtn").attr("onclick","submitOpr();");
    break;
   /*********** 删除 Delete ***********/
   case 3:
    $("#OprType").val(3);
    $('#ModalTitle').html("删除节点");
    msg=''
    +"<input type='hidden' name='id' value='"+id+"'>"
    +"<center><h1>"
    +"<font color='blue'>确定要删除此节点吗？</font><br>"
    +"<font color='green'>【"+name+"】</font>"
    +"</h1></center>";
    $('#msg').html(msg);
    $("#okbtn").attr("onclick","submitOpr();");
    break;
   /*********** 参数错误 Error ***********/
   default:
    $("#OprType").val(0);
    msg="<br><br><center><h1><font color='red'>参数错误，请重试！</font><br><font color='blue'>状态码：</font><font color='green'>"+type+"</font></h1></center><br><br>";
    $('#msg').html(msg);
    $("#okbtn").attr("onclick","$('#myModal').modal('hide');");
    break;
  }
 }
 
 function submitOpr(){
   OprType=$("#OprType").val();
   alert(OprType);
   $("form").submit();
 }
	</script>
	<style>
.ztree li span.button.add {margin-left:2px; margin-right: -1px; background-position:-144px 0; vertical-align:top; *vertical-align:middle}
	</style>

<h1>增 / 删 / 改 节点</h1>
<div class="content_wrap">
	<div class="zTreeDemoBackground left">
		<ul id="treeDemo" class="ztree"></ul>
	</div>
</div>


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
        <form method="post" name="OprNode">
        <input type="hidden" id="OprType" name="OprType">
        <p id="msg"></p>       
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">&lt; 取消</button>
        <button type="button" class="btn btn-success" id='okbtn' onclick="submitOpr()">确定 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->