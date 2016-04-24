<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link href="http://nws.oss-cn-qingdao.aliyuncs.com/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('ueditor/themes/default/css/umeditor.css')?>" type="text/css" rel="stylesheet">
</head>
<body>
<br/>
<table class="table table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>名称</th>
            <th>单位</th>
            <th>开始</th>
            <th>结束</th>
            <th>管理</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($act_list as $key => $arrListValue): ?>
        <tr id="act_<?= $arrListValue['_id'] ?>">
            <td><?= $key + 1 ?></td>
            <td class="act_display_name"><?= $arrListValue['act_name'] ?></td>
            <td><?= $arrListValue['act_school'] ?></td>
            <td><?= $arrListValue['act_start'] ?></td>
            <td><?= $arrListValue['act_end'] ?></td>
            <td>
                <button type="button" class="btn btn-info act_btn_statis"    act_id="<?= $arrListValue['_id'] ?>">统计</button>
                <button type="button" class="btn btn-warning act_btn_modify" act_id="<?= $arrListValue['_id'] ?>">修改</button>
                <button type="button" class="btn btn-danger act_btn_delete"  act_id="<?= $arrListValue['_id'] ?>">删除</button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<!--
模态框准备
-->
<div class="modal fade bs-example-modal-lg" id="act_modify_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <br/>
        <form action="admin_act_manage/modifyActInfo" enctype="multipart/form-data" class="form-horizontal" role="form" id="form_modify_act_info" role="form" method="post">
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
                    <textarea class="form-control" name="act_rule" id="myEditor" style="width:400%; height:240px;"></textarea>
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
                        <?php // foreach ($type as $value): ?>
                            <option><?php //$value ?></option>
                        <?php //endforeach; ?>
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
        </div>
    </div>
</div>


<div class="modal fade bs-example-modal-lg" id="act_delete_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <br/>
        <h1 style="color: red">您确定要删除"<a id="act_delete_modal_display_id"></a>"活动吗?</h1>
            <br/>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-danger">删除</button>
            </div>
        </div>
    </div>
</div>

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
        var dom = {
            modify_modal : $("#act_modify_modal"),
            delete_modal : $("#act_delete_modal"),
            btn_statis   : $(".act_btn_statis"),
            btn_modify   : $(".act_btn_modify"),
            btn_delete   : $(".act_btn_delete")
        };

        var manageAct = {
            init: function(){
                this.bindEvent();
            },

            bindEvent: function(){
                //修改的事件
                dom.btn_modify.bind('click', function () {
                    //填充信息后显示
                    dom.modify_modal.modal('show');
                })

                //获取活动统计的事件
                dom.btn_statis.bind('click', manageAct.getActStatis);

                //绑定删除事件
                dom.btn_delete.bind('click', manageAct.deleteConfirm);

            },

            getActStatis: function(){
                //获取活动统计数据
                window.open('act_statis/dumpStatisToExcel?id=' + $(this).attr('act_id'));
            },

            deleteConfirm: function(){
                //删除活动确认
                var act_name = $(this).parent().parent().find('.act_display_name').html();


                dom.delete_modal.find('#act_delete_modal_display_id').html(act_name);
                dom.delete_modal.modal('show');
            }

        };

        manageAct.init();
    });

//    $(function(){
//        var options = {
//            dataType    : "json",
//            beforeSubmit: function (){
//                $(".btn").attr("value", "正在提交中……请稍后");
//                $(".btn").attr("disabled", "disabled");
//            },
//            success     : function (data){
//                if (data['code'] != 1){
//                    alert(data['error']);
//                } else {
//                    alert('添加成功');
//                    $("#form_add_act").resetForm();
//                }
//                $(".btn").removeAttr("disabled");
//                $(".btn").attr("value", "添加");
//            },
//            error       : function (msg){
//                alert("操作失败");
//                $(".btn").removeAttr("disabled");
//                $(".btn").attr("value", "添加");
//            }
//
//        };
//
//        $("#form_add_act").ajaxForm(options);
//
//
//        //确定选项数目
//        $("#confirm_question_num").click(function(){
//            $("#question_choose_set").empty();
//            var question_indicator = 65;
//            var question_num = $("#question_num").val();
//            for (var i = 0; i < question_num; i++, question_indicator++){
//                $("#question_choose_set").append('<label for="question_choose_' + String.fromCharCode(question_indicator) + '" class="col-sm-2 control-label">' + String.fromCharCode(question_indicator) + '</label><div class="col-sm-9"><input type="text" class="form-control question_choose_input" name="question_choose[]" id="question_choose_' + String.fromCharCode(question_indicator) + '"></div><br/><br/>');
//            }
//        });
//    });
</script>
</html>
