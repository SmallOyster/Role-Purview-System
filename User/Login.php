<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
  <script type="text/javascript" src="https://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
  <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	  <script type="text/javascript" src="../res/js/utils.js"></script>
  <title>登录 / 小生蚝角色权限系统</title>
</head>

<body style="font-family:Microsoft YaHei; background-color:#f9f9f9">
<br>

<div class="container text-center">
<h2>小生蚝角色权限系统 · 登录</h2>

<hr>

<div class="row">
  <div class="well col-md-4 col-md-offset-4 col-sm-10 col-sm-offset-1 text-center col-xs-10 col-xs-offset-1" style="background-color:white">
    <div class="" style="line-height:12px;">
      <p class="text-left"><br>用户名</p>
      <input class="form-control" id="UserName" autocomplete="off" onkeyup="if(event.keyCode==13)$('#Password')[0].focus();">

      <p class="text-left"><br>密码（<a href="ForgetPwd.php" target="_blank" style="font-weight:bolder;color:red;">忘记密码</a>）</p>
      <input type="password" class="form-control" id="Password" onkeyup="if(event.keyCode==13)toLogin();">

      <br>

      <button class="btn btn-primary" style="width:48%" onclick="window.location.href='Reg.php'"> 注 册 Register </button> <button class="btn btn-success" style="width:48%" onclick="toLogin()"> 登 录 Login </button>
      
      <br><br>
      
      <input type="checkbox" id="Remember"> <label for="Remember">记住用户名</label>
    </div>
  </div>
</div>
<hr>
</div>

<script>
window.onload=function(){
  
  /********** ▼ 记住密码 ▼ **********/
  Remember=getCookie("DSTO_RmUN");
  if(Remember!=null){
    $("#UserName").val(Remember);
    $("#Password").focus();
    $("#Remember").attr("checked",true);
  }else{
    $("#UserName").focus();
  }
  /********** ▲ 记住密码 ▲ **********/
}

function toLogin(){
  lockScreen();
  $("input")[0].disabled=1;
  UserName=$("#UserName").val();
  Password=$("#Password").val();
  
  /********** ▼ 记住密码 ▼ **********/
  Remember=$("input[type='checkbox']").is(':checked');
  if(Remember==true){
    setCookie("DSTO_RmUN",UserName);
  }else{
    delCookie("DSTO_RmUN");
  }
  /********** ▲ 记住密码 ▲ **********/
  
  re_Param=getURLParam("re_Param");
  
  if(UserName==""){
    $("#tips").html("请输入用户名！");
    unlockScreen();
    $("input")[0].disabled=0;
    $("#myModal").modal('show');
    return false;
  }
  if(UserName.length<4){
    $("#tips").html("用户名错误！");
    unlockScreen();
    $("input")[0].disabled=0;
    $("#myModal").modal('show');
    return false;  
  }
  if(Password==""){
    $("#tips").html("请输入密码！");
    unlockScreen();
    $("input")[0].disabled=0;
    $("#myModal").modal('show');
    return false;
  }
  if(Password.length<6){
    $("#tips").html("密码错误！");
    unlockScreen();
    $("input")[0].disabled=0;
    $("#myModal").modal('show');
    return false;  
  }
  
  $.ajax({
    url:"toLogin.php",
    type:"post",
    data:{"Name":UserName,"Password":Password,"re_Param":re_Param},
    error:function(e){
      alert(JSON.stringify(e));
      console.log(JSON.stringify(e));
      $("#tips").html("系统错误！");
		    unlockScreen();
		    $("input")[0].disabled=0;
		    $("#myModal").modal('show');
		    return false;
    },
    success:function(got){
      if(got.substr(0,1)=="1"){
      	  URL=got.substr(1);
        window.location.href=URL;
      }else if(got=="UserForbidden"){
        $("#tips").html("当前用户被禁用！<br>请联系管理员！");
		      unlockScreen();
		      $("input")[0].disabled=0;
		      $("#myModal").modal('show');
		      return false;
      }else if(got.substr(0,1)=="2"){
        LoginTime=got.substr(1,19);
        IP=got.substr(20);
        $("#tips").html("您输入的用户名已有授权用户正在使用！本系统不支持一个账号同时登陆！<br><br>必须等对方按“退出系统”按钮后，您方可登陆。<br><br>如果您刚才退出系统时没有按“退出系统”按钮正常退出，</font><font color='green'>请等待10分钟后重新登陆！</font><hr>对方登录时间："+LoginTime+"<br>对方IP："+IP);
        unlockScreen();
        $("input")[0].disabled=0;
        $("#myModal").modal('show');
      }else{
        $("#tips").html("用户名或密码错误！"+got);
		      unlockScreen();
		      $("input")[0].disabled=0;
		      $("#myModal").modal('show');
		      return false;
      }
    }  
  });
}

function lockScreen(){
$('body').append(
  '<div id="lockContent" style="left:50%; margin-left:-20px; top:50%; margin-top:-20px; position:fixed; _position:absolute; height:"+h+"px; width: "+w+"px; z-index: 201; overflow: hidden;">'+
  '<div class="nodata"><img src="../res/img/loading.gif"></img></div>'+
  '</div>'+
  '<div id="lockScreen" style="background: #000; opacity: 0.2; filter:alpha(opacity=20); width: 100%; height: 100%; z-index: 200; position:fixed; _position:absolute; top:0; left:0;">'+
  '</div>'
  );
}

function unlockScreen(){
  $('#lockScreen').remove();
  $('#lockContent').remove();
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
        <form method="post">
          <font color="red" style="font-weight:bolder;font-size:24;text-align:center;">
            <p id="tips"></p>
          </font>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">返回 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->