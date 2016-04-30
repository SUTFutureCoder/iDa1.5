<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link href="http://nws.oss-cn-qingdao.aliyuncs.com/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('ueditor/themes/default/css/umeditor.css')?>" type="text/css" rel="stylesheet">
</head>
<body>
<br/>
    <div id="top" class="col-sm-offset-3">
        <form class="form-inline">
            <div class="form-group col-sm-3">
                <div class="input-group">
                    <input type="text" class="form-control" id="top-search-text" placeholder="Search for...">
                    <span class="input-group-btn">
                        <button class="btn btn-success" id="top-search-submit" type="button">Go!</button>
                    </span>
                </div><!-- /input-group -->
            </div>
            <div class="form-group">
                <select class="form-control" id="select-question-type">
                    <option id="select-question-type-blank">请选择题库名称</option>
                    <?php foreach ($question_type as $questionTypeValue): ?>
                        <option><?= $questionTypeValue ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <select class="form-control" id="select-question-answer-type">
                    <?php foreach ($question_answer_type as $questionAnswerTypeKey => $questionAnswerTypeValue): ?>
                        <option value="<?= $questionAnswerTypeValue ?>"><?= $questionAnswerTypeKey ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <div class="btn-group">
                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        题库操作 <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="#">复制题库</a></li>
                        <li><a href="#">重命名题库</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">清空题库</a></li>
                        <li><a href="#">删除题库</a></li>
                    </ul>
                </div>
            </div>
        </form>
    </div>
    <br/>
    <hr>
    <div id="content">
        <table class="table table-hover" id="content-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>题目内容</th>
                    <th>题库</th>
                    <th>添加时间</th>
                    <th>管理</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

</body>
<script src="http://nws.oss-cn-qingdao.aliyuncs.com/jquery.min.js"></script>
<script src="http://nws.oss-cn-qingdao.aliyuncs.com/bootstrap.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<?= base_url('ueditor/umeditor.config.js') ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?= base_url('ueditor/umeditor.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('ueditor/lang/zh-cn/zh-cn.js') ?>"></script>
<script src="<?= base_url('js/jquery.form.js')?>"></script>
<script>
$(function(){
    var dom  = {
        top     : $("#top"),
        search_text  : $("#top-search-text"),
        search_submit: $("#top-search-submit"),
        content : $("#content"),
        content_table: $("#content-table"),
        question_type        : $("#select-question-type"),
        question_answer_type : $("#select-question-answer-type")
    };

    var page = {
        //分页属性
        page_no : 1,
        perpage : 20
    };

    var funcInit = {
        init: function(){
            this.bindFunc();
        },

        bindFunc: function(){
            //绑定切换下拉菜单动作方法
            dom.question_type.bind('change', this.changeQuestionBank);

            //绑定进行搜索事件
            dom.search_submit.bind('click',  this.submitSearch);
        },

        changeQuestionBank: function(){
            //切换题库
            var post_data = {
                'question_bank_name' : $(this).val(),
                'page' : page
            };

            //提交
            $.ajax({
                type: 'POST',
                url:  'admin_question_manage/getQuestionList',
                data: post_data,
                dataType: 'json',
                success: function (data) {
                    if (data['code'] != 1){
                        alert(data['error']);
                        return;
                    }
                    //开始填充
                },
                error: function(data){
                    alert('操作失败');
                }
            });
        },

        submitSearch: function () {
            var searchText = dom.search_text.val();

            if ('' == searchText){
                alert('请输入搜索关键字');
                return 0;
            }

            $.ajax({
                type: 'POST',
                url:  'admin_question_manage/searchQuestion',
                data: {
                    'search_text' : searchText
                },
                dataType: 'json',
                success: function (data) {
                    if (data['code']){
                        alert(data['error']);
                        return 0;
                    }
                    //开始填充
                    funcInit.displayData(data, 0);
                },
                error: function(data){
                    alert('操作失败');
                }
            });
        },

        displayData: function(data, divide){
            //用于统一显示数据
            var dataLength       = data.length;
            var contentTableBody = dom.content_table.find('tbody');
            contentTableBody.html('');
            for (var i = 0; i < dataLength; ++i){
                var strData = '<tr><td>' + (i + 1) + '</td>' +
                    '<td>' + data[i]['question_content'] + '</td>' +
                    '<td>' + data[i]['question_type'] + '</td>' +
                    '<td>' + data[i]['question_add_time'] + '</td>' +
                    '<td></td></tr>';
                contentTableBody.append(strData);
            }

            //如果分页,则显示页码等信息
            if (divide){
            }
        },

        resetPage: function(){
            //重设分页
            page.page_no = 1;
            page.perpage = 20;
        }
    };

    funcInit.init();
});
</script>
</html>