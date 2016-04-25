<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">    

    <title>工大爱答[Alpha]</title>

    <!-- Bootstrap core CSS -->
    <link href="http://nws.oss-cn-qingdao.aliyuncs.com/bootstrap.min.css" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
  <style type="text/css"></style></head>

  <body>

    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">爱答[alpha]</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">主页</a></li>
            <li><a href="<?= base_url('index.php/about')?>">About</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">            
            <!-- <li class="active"><a href="./"></a></li> -->
            <?php if (!$this->session->userdata('user_name')): ?>
                <li class="active"><a id="login_button" href="#" onclick="showLogin()">登录/注册</a></li>
            <?php else: ?>
                <li class="dropdown active">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?= htmlentities($this->session->userdata('user_name')) ?> <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                    <!-- <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>-->
                    <li class="divider"></li>
                    <!-- <li class="dropdown-header">Nav header</li> -->
                    <li><a id="logout" onclick="logOut()" href="#">注销</a></li>
                    </ul>
                </li>
            <?php endif;?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
        <br/>
        <br/>
        <br/>
        <div class="alert alert-info" role="alert">正在进行的答题</div>
        <?php if (is_array($act_list)): ?>
        <div class="row">
            <?php foreach ($act_list as $key => $value): ?>
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <?php if (!isset($value[0]['act_img'])):?>
                        <img src="img/default.jpg" alt="...">
                    <?php else: ?>
                        <img src="upload/act_img/<?= $value[0]['act_img'] ?>" alt="...">
                    <?php endif; ?>
                    <div class="caption">
                        <h3><?= $value[0]['act_name']?></h3>
                        <p><?= $value[0]['act_comment'] ?></p>
                        <p><botton  class="btn btn-primary" role="button" onclick="joinTest('<?= $key ?>')">参加</botton></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        
        <div class="modal fade bs-example-modal-sm" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-sm">
                <div class="modal-content">
                    <form action="<?= base_url('index.php/index/checkUserLogin')?>" class="form-horizontal" role="form" id="form_login" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">登录/注册</h4>
                    </div>
                    <div class="modal-body">
                            <label class="col-sm-4 control-label" style="font-size:24px;">没有账号？</label>
                            <div class="form-group">
                                <button type="button" class="btn btn-success" onclick="showRegister()">注册</button>
                            </div>  
                        <hr>
                        
                            <div class="form-group">
                                <label for="loginMobile" class="col-sm-2 control-label">手机</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="loginMobile" id="loginMobile">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="loginPassword" class="col-sm-2 control-label">密码</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" name="loginPassword" id="loginPassword" placeholder="Password">
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label for="loginValidateCode" class="col-sm-2 control-label">验证码</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="loginValidateCode" id="loginValidateCode">
                                    <img id="loginImg" title="点击刷新" onclick="this.src='<?php echo base_url('/index.php/index/setValidateCode/setValidateCode');?>/' + Math.random();"/>
                                </div>
                            </div> 
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <input type="submit" class="btn btn-primary" id="submit_login" value="登录">
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade bs-example-modal-sm" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-sm">
                <form action="<?= base_url('index.php/index/checkUserRegister')?>" class="form-horizontal" role="form" id="form_register" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">注册</h4>
                    </div>
                    <div class="modal-body">                        
                            <div class="form-group">
                                <label for="registerTele" class="col-sm-2 control-label">手机号码</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" required="required" name="registerTele" id="registerTele">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="registerMail" class="col-sm-2 control-label">邮箱号码</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="registerMail" id="registerMail">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="registerPassword" class="col-sm-2 control-label">密码</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" name="registerPassword" id="registerPassword" placeholder="Password">
                                </div>
                            </div>                           
                            <div class="form-group">
                                <label for="registerPasswordConfirm" class="col-sm-2 control-label">密码确认</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" name="registerPasswordConfirm" id="registerPasswordConfirm" placeholder="Password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="registerName" class="col-sm-2 control-label">姓名</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="registerName" id="registerName">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="registerNumber" class="col-sm-2 control-label">学号</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="registerNumber" id="registerNumber">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="registerSchool" class="col-sm-2 control-label">就读学校</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="registerSchool" id="registerSchool">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="registerMajor" class="col-sm-2 control-label">专业班级</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="registerMajor" id="registerMajor">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="registerValidateCode" class="col-sm-2 control-label">验证码</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="registerValidateCode" id="registerValidateCode">
                                    <img id="registerImg" title="点击刷新" onclick="this.src='<?php echo base_url('/index.php/index/setValidateCode/setValidateCode');?>/' + Math.random();"/>
                                </div>
                            </div> 
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="submit" id="submit_register" class="btn btn-primary">注册</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
        
        <div class="modal fade bs-example-modal-lg" id="join_test_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" id="join_test_modal_content">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td class="col-sm-2">活动名称</td><td class="join_modal_info" id="join_act_name"></td>
                            </tr>
                            <tr>
                                <td>活动说明</td><td class="join_modal_info" id="join_act_comment"></td>
                            </tr>
                            <tr>
                                <td>开始时间</td><td class="join_modal_info" id="join_act_start"></td>
                            </tr>
                            <tr>
                                <td>结束时间</td><td class="join_modal_info" id="join_act_end"></td>
                            </tr>
                            <tr>
                                <td>创建单位</td><td class="join_modal_info" id="join_act_school"></td>
                            </tr>
                            <tr>
                                <td>答题时限</td><td class="join_modal_info" id="join_act_paper_time"></td>
                            </tr>
                            <tr>
                                <td>规则说明</td><td class="join_modal_info" id="join_act_rule"></td>
                            </tr>
                            <tr>
                                <td><button type="button" class="btn btn-default btn-block" data-dismiss="modal" >关闭</button></td>
                                <td><form action="<?= base_url('index.php/test/Paper')?>" method="POST"><input type="text" hidden="hidden" name="act_id" id="test_post_value"><input type="submit" type="button" class="btn btn-success btn-block" value="已知晓并准备就绪"></form></td>
                            </tr>
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div> 

    <script src="http://nws.oss-cn-qingdao.aliyuncs.com/jquery.min.js"></script>
    <script src="http://nws.oss-cn-qingdao.aliyuncs.com/bootstrap.min.js"></script>
    
    <script src="<?= base_url('js/jquery.form.js')?>"></script>
    <script src="<?= base_url('js/json.js')?>"></script>
    <script>
        <?php if (!$this->session->userdata('user_id')): ?>
        var logined = 0;
        <?php else: ?>
        var logined = 1;
        <?php endif;?>
            
        $(function(){            
            var login_options = {
                dataType    : "json",
                beforeSubmit: function (){
                    $("#submit_login").attr("value", "正在提交中……请稍后");
                    $("#submit_login").attr("disabled", "disabled");
                },
                success     : function (data){
                    if (1 != data['code']){
                        alert(data['error']);
                    } else {
                        $("#loginModal").modal('toggle');
                        $(".navbar-right").empty();  
                        $(".navbar-right").append('<li class="dropdown active">' + 
                    '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">' + data['user_name'] + '<span class="caret"></span></a>' + 
                    '<ul class="dropdown-menu" role="menu">' + 
                    '<li class="divider"></li>' +                     
                    '<li><a id="logout" onclick="logOut()" href="#">注销</a></li>' + 
                    '</ul>'+
                '</li>');
                        $("#login_button").removeAttr("onclick");
                        $("#form_login").resetForm();
                        logined = 1;
                    }
                    $("#submit_login").removeAttr("disabled");
                    $("#submit_login").attr("value", "登录");
                },
                error       : function (msg){
                    alert("操作失败");
                    $("#submit_login").removeAttr("disabled");
                    $("#submit_login").attr("value", "登录");
                }

            };

            $("#form_login").ajaxForm(login_options);
            
            var register_options = {
                dataType    : "json",
                beforeSubmit: function (){
                    $("#submit_login").attr("value", "正在提交中……请稍后");
                    $("#submit_login").attr("disabled", "disabled");
                },
                success     : function (data){
                    if (1 != data['code']){
                        alert(data['error']);
                    } else {
                        $(".navbar-right").empty();
                        $(".navbar-right").append('<li class="dropdown active">' + 
                    '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">' + $('#registerName').val() + '<span class="caret"></span></a>' + 
                    '<ul class="dropdown-menu" role="menu">' + 
                    '<li class="divider"></li>' +                     
                    '<li><a id="logout" onclick="logOut()" href="#">注销</a></li>' + 
                    '</ul>'+
                '</li>');
                        
                        $("#form_register").resetForm();
                        $("#registerModal").modal('toggle');
                        $("#login_button").html(data['user_name']);
                        $("#login_button").removeAttr("onclick");
                        $("#form_login").resetForm();
                        logined = 1;
                    }
                    $("#submit_register").removeAttr("disabled");
                    $("#submit_register").attr("value", "注册");
                },
                error       : function (msg){
                    alert("操作失败");
                    $("#submit_register").removeAttr("disabled");
                    $("#submit_register").attr("value", "注册");
                }

            };

            $("#form_register").ajaxForm(register_options);
            
        });
    </script>
    
    <script>
        function logOut(){
            $.post(
                 '<?= base_url('index.php/index/logout')?>',
                 {
                     logout : '1'
                 },
                 function (data){
                     if ('success' == data){
                         logined = 0;
                         $(".dropdown").remove();
                         $(".navbar-right").append('<li class="active"><a id="login_button" herf="#" onclick="showLogin()">登录/注册</a></li>');
                     }
                 }
            ) 
        }
        
        function joinTest(act_id){           
            
            if (logined == 0){
                showLogin();
            } else {
                $(".join_modal_info").empty();
                $.post(
                    '<?= base_url('index.php/index/getActInfo')?>',
                    {
                        act_id : act_id
                    },
                    function (data){
                        var data = JSON.parse(data); 
                        if ('1' == data['code']){
//                            console.log(data['act_info'])
                            $("#join_act_name").html("<p>" + data['act_info']['act_name'] + "</p>");
                            $("#join_act_comment").html("<p>" + data['act_info']['act_comment'] + "</p>");
                            $("#join_act_start").html("<p>" + data['act_info']['act_start'] + "</p>");
                            $('#join_act_end').html("<p>" + data['act_info']['act_end'] + "</p>");
                            $('#join_act_school').html("<p>" + data['act_info']['act_school'] + "</p>");
                            $('#join_act_paper_time').html("<p>" + data['act_info']['act_paper_time'] + "</p>");
                            $('#join_act_rule').html("<p>" + data['act_info']['act_rule'] + "</p>");
                            
                            
                            $('#test_post_value').attr('value', act_id);
                            
                            
                            $('#join_test_modal').modal('toggle');
                        } else {
                            alert(data['error']);
                        }
                    }
                ) 
            }
        }
        
        function showLogin(){
            $('#loginImg').removeAttr('src');
            $('#loginImg').attr('src', '<?php echo base_url('/index.php/index/setValidateCode/setValidateCode');?>');
            $('#loginModal').modal('toggle');
        }
        
        function showRegister(){
            $('#loginModal').modal('hide');
            $('#registerImg').removeAttr('src');
            $('#registerImg').attr('src', '<?php echo base_url('/index.php/index/setValidateCode/setValidateCode');?>');
            $('#registerModal').modal('toggle');
        }
        
        
    </script>
    </body>
</html>