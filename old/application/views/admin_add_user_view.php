<!DOCTYPE html>  
<html>  
<head>  
    <title></title>     
    <link href="http://nws.oss-cn-qingdao.aliyuncs.com/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <br/>
    <form action="admin_add_user/addUser" class="form-horizontal" role="form" id="form_add_user" method="post">
    <div class="form-group">
        <label for="user_telephone" class="col-sm-2 control-label">手机号码</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="user_telephone" id="user_telephone">
        </div>
    </div>
    <div class="form-group">
        <label for="user_mail" class="col-sm-2 control-label">邮箱号码</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="user_mail" id="user_mail">
        </div>
    </div>
    <div class="form-group">
        <label for="user_password" class="col-sm-2 control-label">密码</label>
        <div class="col-sm-9">
            <input type="password" class="form-control" name="user_password" id="user_password">
        </div>
    </div>
    <div class="form-group">
        <label for="user_password_confirm" class="col-sm-2 control-label">密码确认</label>
        <div class="col-sm-9">
            <input type="password" class="form-control" name="user_password_confirm" id="user_password_confirm">
        </div>
    </div>
    <div class="form-group">
        <label for="user_name" class="col-sm-2 control-label">姓名</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="user_name" id="user_name">
        </div>
    </div>
    <div class="form-group">
        <label for="user_number" class="col-sm-2 control-label">学号</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="user_number" id="user_number">
        </div>
    </div>
    <div class="form-group">
        <label for="user_school" class="col-sm-2 control-label">学校</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="user_school" id="user_school">
        </div>
    </div>
    <div class="form-group">
        <label for="user_major" class="col-sm-2 control-label">专业</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="user_major" id="user_major">
        </div>
    </div>
    <div class="form-group">
        <label for="user_role" class="col-sm-2 control-label">角色</label>
        <div class="col-sm-9">
            <select class="form-control" name="user_role" id="user_role">
                <?php foreach ($role_list as $value): ?>
                    <option><?= $value ?></option>
                <?php endforeach; ?>
            </select> 
        </div>
    </div>
    <hr>
    <div class="col-sm-10 col-sm-offset-1">
        <input type="submit" class="form-control btn btn-success" value="提交">
    </div>
    <br/>
    <br/>
    <hr>
    </form>
</body>
    <script src="http://nws.oss-cn-qingdao.aliyuncs.com/jquery.min.js"></script>
    <script src="http://nws.oss-cn-qingdao.aliyuncs.com/bootstrap.min.js"></script>
    
    <script src="<?= base_url('js/jquery.form.js')?>"></script>
    <script>
        $(function(){
            var options = {
            dataType    : "json",
            beforeSubmit: function (){
                $(".btn").attr("value", "正在提交中……请稍后");
                $(".btn").attr("disabled", "disabled");
            },
            success     : function (data){ 
                console.log(data);
                if (1 != data['code']){
                    alert(data['error']);
                } else {
                    alert('添加成功');
                    $("#form_add_user").resetForm();
                }
                $(".btn").removeAttr("disabled");
                $(".btn").attr("value", "添加");
            },
            error       : function (msg){
                alert("操作失败");
                $(".btn").removeAttr("disabled");
                $(".btn").attr("value", "添加");
            }
            };

            $("#form_add_user").ajaxForm(options);
        })    
    </script>
</html>