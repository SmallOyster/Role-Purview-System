<meta name="viewport" content="width=device-width, initial-scale=1">
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
		},
		callback: {
			onCheck: onCheck
		}
	};

		var zNodes =[
			{ id:1, pId:0, name:"随意勾选 1", open:true},
			{ id:11, pId:1, name:"随意勾选 1-1"},
			{ id:12, pId:1, name:"随意勾选  1-2", open:true},
			{ id:121, pId:12, name:"随意勾选 1-2-1", checked:true},
			{ id:13, pId:1, name:"随意勾选 1-3"},
			{ id:2, pId:0, name:"随意勾选 2", open:true},
			{ id:21, pId:2, name:"随意勾选 2-1"},
			{ id:22, pId:2, name:"随意勾选 2-2", open:true},
			{ id:221, pId:22, name:"随意勾选 2-2-1", checked:true},
			{ id:23, pId:2, name:"随意勾选 2-3", checked:true}
		];

	var clearFlag = false;
	function onCheck(e,treeId,treeNode){
		count();
		if(clearFlag){
			clearCheckedOldNodes();
		}
	}

	function clearCheckedOldNodes(){
		var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
		nodes = zTree.getChangeCheckedNodes();
		for (var i=0,l=nodes.length; i<l; i++){
			nodes[i].checkedOld=nodes[i].checked;
		}
	}

	function count() {
		var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
		checkCount = zTree.getCheckedNodes(true).length,
		nocheckCount = zTree.getCheckedNodes(false).length,
		changeCount = zTree.getChangeCheckedNodes().length;
		$("#checkCount").text(checkCount);
		$("#nocheckCount").text(nocheckCount);
		$("#changeCount").text(changeCount);
	}

	function createTree() {
		$.fn.zTree.init($("#treeDemo"),setting,zNodes);
		count();
	}

	$(document).ready(function(){
		createTree();
	});
</script>

<h1>checkbox 勾选统计</h1>
<div class="content_wrap">
	<div class="zTreeDemoBackground left">
		<ul id="treeDemo" class="ztree"></ul>
	</div>
</div>