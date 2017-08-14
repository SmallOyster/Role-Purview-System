<style>
.EptCache_tbl{
  text-align:center;
  font-weight:bolder;
  font-size:18px;
  color:blue;
}
</style>

<center>
  <h1>清空系统缓存</h1>
</center>
<hr>

<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
<tr>
  <th>缓存类型</th>
  <!--<th>总条数</th>-->
  <th>清空</th>
</tr>

<?php
$AllCache=$GB_Sets->G("CacheList",2,"System");
$AllCache=explode("|",$AllCache);

foreach($AllCache as $value){
  $Cache=explode(",",$value);
  $CacheName=$Cache[0];
  $CacheType=$Cache[1];
?>
<tr>
  <td class="EptCache_tbl"><?php echo $CacheName; ?></td>
  <!--<td></td>-->
  <td><button class="btn btn-danger" style="width:98%" onclick='toVerify("<?php echo $CacheType; ?>","<?php echo $CacheName; ?>");'>清 空 Clear</button></td>
</tr>
<?php } ?>

</table>

<input type="hidden" id="CacheType">
<input type="hidden" id="CacheName">

<script>
var toURL="Sys/toEmptyCache.php";
var NoPwd="请输入密码！";
var ErrMSG="系统错误！";

function toVerify(CacheType,CacheName){
  $("#CacheType").val(CacheType);
  $("#CacheName").val(CacheName);
  $("#VerifyModal").modal("show");
}

function toEmptyCache(){
  lockScreen();
  Pwd=$("#VerifyPwd").val();
  if(Pwd==""){
    showError(NoPwd);
  }
  
  CacheType=$("#CacheType").val();
  CacheName=$("#CacheName").val();

  $.ajax({
    url:toURL,
    type:"post",
    data:{"Password":Pwd,"CacheType":CacheType,"CacheName":CacheName},
    error:function(e){
      alert(JSON.stringify(e));
      console.log(JSON.stringify(e));
      showError(ErrMSG);
      return false;
    },
    success:function(got){
      if(got=="1"){
        alert("清空成功！");
        location.reload();
      }else if(got=="PasswordErr"){
        showError("密码认证失败！");
      }else{
        showError(ErrMSG+got);
      }
    }
  });
}

function showError(TipsContent){
  $("#Tips").html(TipsContent);
  $("#VerifyModal").modal("hide");
  $("#myModal").modal("show");
  $("#VerifyPwd").val("");
  unlockScreen();
}
</script>

<div class="modal fade" id="VerifyModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="ModalTitle">身份认证</h3>
      </div>
      <div class="modal-body">
        您的密码：<input type="password" id="VerifyPwd" required>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">返回 &lt;</button> <button type="button" class="btn btn-danger" onclick="toEmptyCache()">确定 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="ModalTitle">温馨提示</h3>
      </div>
      <div class="modal-body">
        <form method="post">
          <font color="red" style="font-weight:bolder;font-size:26;text-align:center;">
            <p id="Tips"></p>
          </font>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">返回 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->