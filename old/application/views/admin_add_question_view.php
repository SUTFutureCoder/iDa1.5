<!DOCTYPE html>  
<html>  
<head>  
    <title></title>     
    <meta charset="utf-8">
    <link href="http://nws.oss-cn-qingdao.aliyuncs.com/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('ueditor/themes/default/css/umeditor.css')?>" type="text/css" rel="stylesheet">
</head>
<body>
    <br/>
    <form action="admin_add_question/setQuestion" class="form-horizontal" role="form" id="form_add_question" method="post">
    <div class="form-group">
        <label for="question_type" class="col-sm-2 control-label">题目类型</label>
        <div class="col-sm-9">
            <?php if (!empty($question_type_list)): ?>
            <select class="form-control" name="question_type_select" id="question_type_select">
                <?php foreach ($question_type_list as $value): ?>
                    <option><?= $value ?></option>
                <?php endforeach; ?>
            </select>
            <br/>
            <?php endif; ?>
            <input type="text" class="form-control" placeholder="如不在选项中请在此添加类型" name="question_type_fill" id="question_type_fill">
        </div>
    </div>
    
    <div class="form-group">
        <label for="question_content" class="col-sm-2 control-label">题目正文</label>
        <div class="col-sm-9">
            <textarea class="form-control" name="question_content" id="question_content" rows="3"></textarea>
        </div>
    </div>
        
    <hr>
    
    
    <div class="form-group">
        <label for="question_num" class="col-sm-2 control-label">选项个数</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="question_num" id="question_num">
        </div>
    </div>
    <div class="col-sm-10 col-sm-offset-1">
        <input class="form-control btn btn-info" id="confirm_question_num" value="生成选项">
    </div>
    <br/>
    <br/>
    <div class="form-group" id="question_choose_set">
    </div>
    <br/>
    <div class="form-group">
        <label for="question_choose_answer" class="col-sm-2 control-label">选择题正确答案</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="question_choose_answer" placeholder="多选请用空格分离输入 A B C" id="question_choose_answer">
        </div>
    </div>
    <hr>
    
    <div class="form-group">
        <label for="question_fill_answer" class="col-sm-2 control-label">填空答案</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="question_fill_answer" id="question_fill_answer">
        </div>
    </div>

    <hr>
    
    <div class="form-group">
        <label for="question_judge" class="col-sm-2 control-label">是否为判断题</label>
        <div class="col-sm-9">
            <input type="checkbox" name="question_judge" id="question_judge" >
        </div>
    </div>
    <div class="form-group">
        <label for="question_judge_true" class="col-sm-2 control-label">是否正确</label>
        <div class="col-sm-9">
            <input type="checkbox" name="question_judge_true" id="question_judge_true" >
        </div>
    </div>
    
    <hr>
    
    <div class="form-group">
        <label for="question_score" class="col-sm-2 control-label">题目分值</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="question_score" id="question_score">
        </div>
    </div>
    
    <div class="form-group">
        <label for="question_private" class="col-sm-2 control-label">是否私有</label>
        <div class="col-sm-9">
            <input type="checkbox" name="question_private" id="question_private" >
        </div>
    </div>
    
    <div class="form-group">
        <label for="question_hint" class="col-sm-2 control-label">题目提示</label>
        <div class="col-sm-9">
            <textarea type="text/plain" id="myEditor" name="question_hint" style="width:100%;height:240px;"></textarea>
        </div>
    </div>
    
    
    
    <br/>
    <br/>
    <hr>
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
        $(function(){
            var options = {
                dataType    : "json",
                beforeSubmit: function (){
                    $(".btn").attr("value", "正在提交中……请稍后");
                    $(".btn").attr("disabled", "disabled");
                },
                success     : function (data){
                    if (1 != data['code']){
                        alert(data['error']);
                    } else {
                        alert('添加成功');
                        $("#form_add_question").resetForm();
                    }
                    $(".btn").removeAttr("disabled");
                    $(".btn").attr("value", "添加");
                },
                error       : function (msg){
                    console.log(msg);
                    alert("操作失败");
                    $(".btn").removeAttr("disabled");
                    $(".btn").attr("value", "添加");
                }

            };

            $("#form_add_question").ajaxForm(options);
            
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
    <script type="text/javascript">
    //实例化编辑器
    var um = UM.getEditor('myEditor');    
</script>
</html>