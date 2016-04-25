<!DOCTYPE html>  
<html>  
<head>  
    <title></title>     
    <link href="http://nws.oss-cn-qingdao.aliyuncs.com/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('ueditor/themes/default/css/umeditor.css')?>" type="text/css" rel="stylesheet">
</head>
<body>
    <br/>
    <form action="admin_add_act/addAct" enctype="multipart/form-data" class="form-horizontal" role="form" id="form_add_act" role="form" method="post">
    <div class="form-group">
        <label for="act_name" class="col-sm-2 control-label">活动名称</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="act_name" id="act_name">
        </div>
    </div>
    <div class="form-group">
        <label for="act_comment" class="col-sm-2 control-label">活动说明</label>
        <div class="col-sm-9">
            <textarea class="form-control" name="act_comment" id="act_comment" rows="3"></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="act_rule" class="col-sm-2 control-label">规则说明</label>
        <div class="col-sm-9">
            <textarea class="form-control" name="act_rule" id="myEditor" style="width:100%;height:240px;"></textarea>
        </div>
    </div>
    <hr>    
    <div class="form-group">
        <label for="act_private" class="col-sm-2 control-label">活动私有</label>
        <div class="col-sm-9">
            <input type="checkbox" name="act_private" id="act_private" >
        </div>
    </div>
    
    <div class="form-group">
        <label for="act_school" class="col-sm-2 control-label">活动学校</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="act_school" id="act_school">
        </div>
    </div>
    <hr>    
    <div class="form-group">
        <label for="act_paper_time" class="col-sm-2 control-label">答题时限(分)</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="act_paper_time" id="act_paper_time">
        </div>
    </div>
    <div class="form-group">
        <label for="act_start" class="col-sm-2 control-label">开始时间</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="act_start" placeholder="2015-03-26 21:21:00" id="act_start">
        </div>
    </div>
    <div class="form-group">
        <label for="act_end" class="col-sm-2 control-label">结束时间</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="act_end"  placeholder="2015-05-26 00:00:00" id="act_end">
        </div>
    </div>
        
    <div class="form-group">
        <label for="act_question_type" class="col-sm-2 control-label">题库类型</label>
        <div class="col-sm-9">
            <select class="form-control" name="act_question_type" id="act_question_type">
                <?php foreach ($type as $value): ?>
                    <option><?= $value ?></option>
                <?php endforeach; ?>
            </select> 
        </div>
        
    </div>
    <hr>
    <div class="form-group">
        <label for="act_question_choose_sum" class="col-sm-2 control-label">单选数量</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="act_question_choose_sum" value="0" id="act_question_choose_sum">
        </div>
    </div>
    
    <div class="form-group">
        <label for="act_question_multi_choose_sum" class="col-sm-2 control-label">多选数量</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="act_question_multi_choose_sum" value="0" id="act_question_multi_choose_sum">
        </div>
    </div>
    
    <div class="form-group">
        <label for="act_question_judge_sum" class="col-sm-2 control-label">判断数量</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="act_question_judge_sum" value="0" id="act_question_judge_sum">
        </div>
    </div>
    
    <div class="form-group">
        <label for="act_question_fill_sum" class="col-sm-2 control-label">填空数量</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="act_question_fill_sum" value="0" id="act_question_fill_sum">
        </div>
    </div>
    
    
    <hr>
    
    <div class="form-group">
        <label for="upload_img" class="col-sm-2 control-label">预览图上传</label>
        <div class="col-sm-9">
            <input class="form-control"  type="file" name="upload_img" id="upload_img">
            <p class="help-block">推荐450*243</p>
        </div>
    </div>
    
    <div class="col-sm-10 col-sm-offset-1">
        <input type="submit" class="form-control btn btn-success" id="submit" value="提交">
    </div>
    <br/>
    <br/>
    <hr>
    </form>
</body>
    <script src="http://nws.oss-cn-qingdao.aliyuncs.com/jquery.min.js"></script>
    <script src="http://nws.oss-cn-qingdao.aliyuncs.com/bootstrap.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?= base_url('ueditor/umeditor.config.js') ?>"></script>
    <script type="text/javascript" charset="utf-8" src="<?= base_url('ueditor/umeditor.min.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('ueditor/lang/zh-cn/zh-cn.js') ?>"></script>
    <script src="<?= base_url('js/jquery.form.js')?>"></script>
    <script>
        //实例化编辑器
        var um = UM.getEditor('myEditor');    
        $(function(){
            var options = {
                dataType    : "json",
                beforeSubmit: function (){
                    $(".btn").attr("value", "正在提交中……请稍后");
                    $(".btn").attr("disabled", "disabled");
                },
                success     : function (data){
                    if (data['code'] != 1){
                        alert(data['error']);
                    } else {
                        alert('添加成功');
                        $("#form_add_act").resetForm();
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

            $("#form_add_act").ajaxForm(options);
            
            
            //确定选项数目
            $("#confirm_question_num").click(function(){
                $("#question_choose_set").empty();
                var question_indicator = 65;
                var question_num = $("#question_num").val();
                for (var i = 0; i < question_num; i++, question_indicator++){
                    $("#question_choose_set").append('<label for="question_choose_' + String.fromCharCode(question_indicator) + '" class="col-sm-2 control-label">' + String.fromCharCode(question_indicator) + '</label><div class="col-sm-9"><input type="text" class="form-control question_choose_input" name="question_choose[]" id="question_choose_' + String.fromCharCode(question_indicator) + '"></div><br/><br/>');
                }
            });
        });   
    </script>
</html>
