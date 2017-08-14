<h2 style="text-align:center">
  欢迎登录<br>小生蚝角色权限系统！
</h2>
<script>
var GlobalNotice="";

window.onload=function(){
  getGlobalNotice();
};

function getGlobalNotice(){
  $.ajax({
    url:"Functions/Api/getGlobalNotice.php",
    type:"get",
    dataType:"json",
    success:function(got){
    	if(got.Content!="" && got.PubTime!=""){
    		Content=got.Content;
    		PubTime=got.PubTime;
    		msg="发布时间：<b>"+PubTime+"</b><hr>"+Content;
    		dm_notification(msg,'green',7000);
    	}
    }
	});
}
</script>