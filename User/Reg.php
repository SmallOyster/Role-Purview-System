<html>
<head>
	<meta name="viewport" content="wIDth=device-wIDth, initial-scale=1">
	<link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<script type="text/javascript" src="https://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
	<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../res/js/utils.js"></script>
	<title>注册 / 小生蚝角色权限系统</title>
</head>

<body>
<br>
<div class="well col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center col-xs-10 col-xs-offset-1">
  <img src="../res/img/back.png" style="position:absolute;wIDth:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回">
  <h3>注 册</h3><br>
  <div class="col-md-offset-2" style="line-height:12px;">
    <div class="input-group">
      <span class="input-group-addon">用户名</span>
      <input class="form-control" ID="UserName" autocomplete="off" onkeyup="if(event.keyCode==13)$('#RealName')[0].focus();">
      <span class="input-group-addon" ID="forgot">&lt;</span>
    </div>
    <div class="input-group">
      <span class="input-group-addon">真实姓名</span>
      <input class="form-control" ID="RealName" autocomplete="off" onkeyup="if(event.keyCode==13)$('#Phone')[0].focus();">
      <span class="input-group-addon" ID="forgot">&lt;</span>
    </div>

    <hr>

    <div class="input-group">
      <span class="input-group-addon">手机号</span>
      <input class="form-control" ID="Phone" autocomplete="off" onkeyup="if(event.keyCode==13 || this.value.length==11)$('#Email')[0].focus();">
      <span class="input-group-addon" ID="forgot">&lt;</span>
    </div>
    <div class="input-group">
      <span class="input-group-addon">邮箱</span>
      <input class="form-control" ID="Email" autocomplete="off" onkeyup="if(event.keyCode==13)$('#Password')[0].focus();">
      <span class="input-group-addon" ID="forgot">&lt;</span>
    </div>

    <hr>

    <div class="input-group">
      <span class="input-group-addon">密码</span>
      <input type="password" class="form-control" ID="Password" autocomplete="off" onkeyup="if(event.keyCode==13)$('#vrf_Password')[0].focus();">
      <span class="input-group-addon" ID="forgot">&lt;</span>
    </div>
    <div class="input-group">
      <span class="input-group-addon">确认密码</span>
      <input type="password" class="form-control" ID="vrf_Password" onkeyup="if(event.keyCode==13)toReg();">
      <span class="input-group-addon" ID="forgot">&lt;</span>
    </div>
    <hr>
    <button class="btn btn-success" style="wIDth:100%" onclick="toReg()"> 注 册</button>
  </div>
</div>

<script>
function toReg(){
  lockScreen();
  $("input").attr("disabled","disabled");
  name=$("#UserName").val();
  RealName=$("#RealName").val();
  Phone=$("#Phone").val();
  Email=$("#Email").val();
  pw=$("#Password").val();
  vrf_pw=$("#vrf_Password").val();

	if(name==""){
		showModalErr("请输入用户名！");
		return false;
	}
  if(name.length<6){
    showModalErr("用户名长度必须大于6位！<br>请重新输入！");
    return false;
  }
	if(RealName==""){
		showModalErr("请输入真名！");
		return false;
	}
  if(isChn(RealName)==0 || RealName.length<2){
    showModalErr("您的真实姓名有误！<br>请重新输入！");
    return false;
  }
  if(Phone==""){
    showModalErr("请输入手机号！");
    return false;
  }
  if(Phone.length!=11){
    showModalErr("请输入正确的手机号码！");
    return false;
  }
  if(Email==""){
    showModalErr("请输入邮箱！");
    return false;
  }
	if(pw==""){
		showModalErr("请输入密码！");
		return false;
	}
  if(pw.length<6){
    showModalErr("密码长度必须大于6位！<br>请重新输入！");
    return false;
  }
	if(vrf_pw==""){
		showModalErr("请再次输入密码！");
		return false;
	}
	if(vrf_pw != pw){
		showModalErr("两次输入的密码不相同！<br>请重新输入！");
		return false;
	}
	
	$.ajax({
    url:"toReg.php",
    type:"post",
    data:{"Name":name,"Phone":Phone,"Email":Email,"Password":pw,"RealName":RealName},
    error:function(e){
      alert(JSON.stringify(e));
      console.log(JSON.stringify(e));
      $("#tips").html("系统错误！");
		  unlockScreen();
		  unlockInput(4);
		  $("#myModal").modal('show');
		  return false;
    },
    success:function(got){
      if(got.substr(0,1)=="1"){
        alert("注册成功！");
        window.location.href="Login.php";
      }else if(got=="HaveUser"){
        showModalErr("此用户名已存在！请更换用户名！");
        return false;
      }else if(got=="NoRole"){
        showModalErr("不存在默认角色！请联系管理员！");
        return false;
      }else if(got=="InsertErr"){
        showModalErr("数据新增失败！请联系管理员！");
        return false;
      }else{
        showModalErr("服务器错误！\\n\\n请提交错误码给管理员：\\n"+got);
        return false;
      }
    }  
  });
}

function showModalErr(content){
  $("#tips").html(content);
  unlockScreen();
  $("#myModal").modal('show');
  unlockInput(4);
}

function unlockInput(total){
  for(i=0;i<total;i++){
    $("input")[i].disabled=0;
  }
}

function lockScreen(){
$('body').append(
  '<div ID="lockContent" style="left:50%; margin-left:-20px; top:50%; margin-top:-20px; position:fixed; _position:absolute; height:"+h+"px; wIDth: "+w+"px; z-index: 201; overflow: hIDden;">'+
  '<div class="nodata"><img src="../res/img/loading.gif"></img></div>'+
  '</div>'+
  '<div ID="lockScreen" style="background: #000; opacity: 0.2; filter:alpha(opacity=20); wIDth: 100%; height: 100%; z-index: 200; position:fixed; _position:absolute; top:0; left:0;">'+
  '</div>'
  );
}

function unlockScreen(){
  $('#lockScreen').remove();
  $('#lockContent').remove();
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
        <form method="post">
          <font color="red" style="font-weight:bolder;font-size:26;text-align:center;">
            <p ID="tips"></p>
          </font>
        </form>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">返回 &gt;</button>
    </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->