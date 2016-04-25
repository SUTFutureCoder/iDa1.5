<!DOCTYPE html>  
<html>  
<head>  
    <title></title>  
    <meta charset="utf-8">
    <link href="http://nws.oss-cn-qingdao.aliyuncs.com/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('ueditor/themes/default/css/umeditor.css')?>" type="text/css" rel="stylesheet">
</head>
<body>
    <div class="container">
    <br/>
    <?php if(isset($answer_fin) && $answer_fin == 1): ?>
    <div class="panel panel-default ">
        <div class="panel-heading">您已经完成此试题</div>
        <table class="table">
            <tr>
                <td>您的得分</td><td><?= $history['answer_score'] ?></td>
            </tr>
            <tr>
                <td>开始时间</td><td><?= $history['start_time'] ?></td>
            </tr>
            <tr>
                <td>结束时间</td><td><?= $history['end_time'] ?></td>
            </tr>
            <tr>
                <td>当前排名</td><td id="rank">100+</td>
            </tr>
            <tr>
                <td colspan="2"><button type="button" onclick="window.location.href='<?= base_url() ?>'" class="btn btn-primary btn-md btn-block">返回</button></td>
            </tr>
        </table>
    </div>
    <hr>
    <div class="panel panel-info">
        <div class="panel-heading"><?= $act_info['act_name'] ?>统计</div>
        <table class="table table-striped">
            <tr>
                <th>参与人数</th>
                <td><?= $act_statis['join'] ?></td>
            </tr>
            <tr>
                <th>最高分</th>
                <td><?= $act_statis['score']['result'][0]['max_score'] ?></td>
            </tr>
            <tr>
                <th>最低分</th>
                <td><?= $act_statis['score']['result'][0]['min_score'] ?></td>
            </tr>
            <tr>
                <th>平均分</th>
                <td><?= number_format($act_statis['score']['result'][0]['average_score'], 2) ?></td>
            </tr>
            <tr>
                <th>平均用时</th>
                <td><?= number_format($act_statis['score']['result'][0]['average_time'] / 60, 2) ?></td>
            </tr>
        </table>
    </div>
    <hr>
    <div class="panel panel-success">
        <div class="panel-heading"><?= $act_info['act_name'] ?>排行榜</div>
        <table class="table">
            <thead>
                <th class="col-sm-1">排名</th>
                <th class="col-sm-1">姓名</th>
                <th class="col-sm-1">学校</th>
                <th class="col-sm-1">分数</th>
                <th class="col-sm-1">完成时间</th>
            </thead>
            <tbody>
            <?php $rank = 1 ?>
            <?php foreach ($ranking as $value): ?>
            <?php if ($history['user_id'] == $value['user_id']): ?>
            <script>
                document.getElementById('rank').innerHTML = '<?= $rank ?>';
            </script>
            <tr class="success">
            <?php else: ?>
            <tr>
            <?php endif; ?>
                <th><?= $rank++ ?></th>
                <td><?= $value['user_name'] ?></td>
                <td><?= $value['user_school'] ?></td>
                <td><?= $value['answer_score'] ?></td>
                <td><?= number_format($value['answer_time'] / 60, 2) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="alert alert-success navbar-fixed-top" role="alert">距离结束还有<a id="time_counter_hour"></a>时<a id="time_counter_minute"></a>分<a id="time_counter_second"></a>秒 <button type="button" onclick="$('#finish_modal').modal('toggle')" class="btn btn-danger navbar-right col-sm-offset-1">完成</button><button type="button" onclick="save(0)" class="btn btn-success navbar-right col-sm-offset-1" id="save_button" data-container="body" data-toggle="popover" data-placement="bottom" data-content="保存成功">保存</button></div>
        </div>
        
    </nav>
    <br/>
    <br/>
    <br/>
    
    <?php $now_type = ''; ?>
    <?php $question_id = 1; ?>
    <?php $question_sum = count($question_data_list) ?>
    <?php foreach ($question_data_list as $key => $value): ?>
        <?php if ($now_type != $value['type']): ?>
            <?php switch ($value['type']){
                case 'choose' :
                    echo '<h2>单选题</h2>';
                    break;
                case 'multi_choose' :
                    echo '<h2>多选题</h2>';
                    break;
                case 'fill' :
                    echo '<h2>填空题</h2>';
                    break;
                case 'judge' :
                    echo '<h2>判断题</h2>';
                    break;
            }
            ?>
            <hr>
        <?php $now_type = $value['type'] ?>
        <?php endif; ?>
            <div class="panel panel-default">
                <div class="panel-heading"><?= $question_id ?> . <?= $value['question_content'] ?></div>
                <div class="panel-body">
                    <form>
                        <?php if ($value['type'] == 'choose'): ?>
                            <div class="form-group question_choose" id="question_<?= $question_id - 1 ?>" question_type="choose" question_id="<?= $value['question_id']?>">
                            <?php $choose_option = 'A'?>
                            <?php foreach ($value['question_choose'] as $question_choose): ?>
                                <input type="radio"  name="<?= $value['question_id'] ?>" value="<?= $choose_option++?>"><?= $question_choose?><br/>
                            <?php endforeach;?>
                            </div>
                        <?php endif; ?>

                        <?php if ($value['type'] == 'multi_choose'): ?>
                            <div class="form-group question_multi_choose" id="question_<?= $question_id - 1 ?>" question_type="multi_choose" question_id="<?= $value['question_id'] ?>">
                            <?php $choose_option = 'A'?>
                            <?php foreach ($value['question_choose'] as $question_choose): ?>
                                <input type="checkbox" value="<?= $choose_option++ ?>"><?= $question_choose ?><br/>
                            <?php endforeach;?>
                            </div>
                        <?php endif; ?>

                        <?php if ($value['type'] == 'fill'): ?>
                            <div class="form-group question_fill" id="question_<?= $question_id - 1 ?>" question_type="fill" question_id="<?= $value['question_id'] ?>">
                                <input type="text" class="form-control">
                            </div>
                        <?php endif; ?>

                        <?php if ($value['type'] == 'judge'): ?>
                            <div class="form-group question_judge" id="question_<?= $question_id - 1 ?>" question_type="judge" question_id="<?= $value['question_id'] ?>">
                                <input type="checkbox" >正确
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            <br/>
        <?php $question_id++ ?>
    <?php endforeach; ?>
<?php endif; ?>
                    
    <?php if (isset($out_of_time) && $out_of_time): ?>
    <div class="panel panel-default ">
        <div class="panel-heading">此活动未开始或已过期</div>
        <table class="table">
            <tr>
                <td colspan="2"><button type="button" onclick="window.location.href='<?= base_url() ?>'" class="btn btn-primary btn-md btn-block">返回</button></td>
            </tr>
        </table>
    </div>
    <?php endif;?>
    </div>
    
    
    <div class="modal fade bs-example-modal-sm" id="finish_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                
            <div class="modal-body">
                <h2>您确定完成答卷吗？</h2>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" onclick="save(1)" class="btn btn-danger">确定</button>
            </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</body>
    <script src="http://nws.oss-cn-qingdao.aliyuncs.com/jquery.min.js"></script>
    <script src="http://nws.oss-cn-qingdao.aliyuncs.com/bootstrap.min.js"></script>    
    <script>
        var savetime = new Date().getTime();
        <?php if (isset($user_act_data)): ?>
            //开始倒计时
            var runtimes = 0;
            var left_time = <?= $user_act_data['left_time'] ?>;
            //倒计时
            setInterval("time_counter()", 1000);
            
            //自动保存
            setInterval("save(0)", 600000);
            
            function time_counter(){
                var MS = left_time * 1000 - runtimes * 1000;
                var H = Math.floor(MS / (1000 * 60 * 60)) % 24;
                var M = Math.floor(MS / (1000 * 60)) % 60;
                var S = Math.floor(MS / 1000) % 60;
                
                $("#time_counter_hour").html(H);
                $("#time_counter_minute").html(M);
                $("#time_counter_second").html(S);
                runtimes++;
                
                if (MS == 5 * 59 * 1000){
                    $(".navbar-fixed-top").removeClass('alert-success');
                    $(".navbar-fixed-top").addClass('alert-danger');
                    
                    alert('请注意，还有最后五分钟');
                }
                
                if (MS == 0){
                    save(1);
                }
            }
        <?php endif; ?>
            
            
        <?php if (isset($user_act_data['user_answer_list'])): ?>
            //开始恢复数据
            <?php foreach ($user_act_data['user_answer_list'] as $key => $value): ?>
                switch ($("#question_<?= $key ?>").attr('question_type')){
                    case 'choose':
                        $("#question_<?= $key ?> input[value='<?= $value ?>']").attr('checked', 'checked');
                        break;
                    case 'multi_choose':
                        var multi_choose_data_array = new Array();
                        var multi_choose_data = '<?= $value ?>';
                        multi_choose_data_array = multi_choose_data.split(' ');
                        
                        for (var i in multi_choose_data_array){
                            $("#question_<?= $key ?> input[value='"+ multi_choose_data_array[i] +"']").attr('checked', 'checked');
                        }
                        break;
                    case 'fill':
                        $("#question_<?= $key ?> input").val('<?= $value ?>');
                        break;
                    case 'judge':
                        <?php if ($value): ?>
                                $("#question_<?= $key ?> input").prop('checked', 'checked');
                        <?php endif;?>
                        break;
                }
            <?php endforeach; ?>
        <?php endif;?>
            
        <?php if(!isset($answer_fin)): ?>
        function save(fin){
            if ((new Date().getTime()) <= (savetime + 1000)){
                alert('您的保存过于频繁，请休息一下～');
                return 0;
            }
            
            var json = '{';
            for (i = 0; i < <?= $question_sum ?>; i++){
                if (i != 0){
                    json += ', '
                }
                switch ($('#question_' + i).attr('question_type')){
                    case 'choose':
                        if (!$('#question_' + i + ' input:checked').attr('value')){
                            json += '"' + i + '" : ""';
                        } else {
//                            alert($('#question_' + i + ' input:checked').attr('value'));
                            json += '"' + i + '" : "' + ($("#question_" + i + " input:checked").attr("value")) + '"';
                        }
                        break;
                        
                    case 'multi_choose':
                        json += '"' + i + '" : "';
                        var multi_choose_count = $('#question_' + i + ' input:checked').length
                        if (multi_choose_count){
                            var multi_choose = 0;
                            
                            $('#question_' + i + ' input:checked').each(function(){
                                json += ($(this).attr('value'));
                                if (multi_choose != multi_choose_count - 1){
                                    json += ' ';
                                }
                                multi_choose++;
//                                alert($(this).attr('value'));
                            });
                        }
                        json += '"';
                        break;
                        
                    case 'fill':
                            json += '"' + i + '" : "' + ($('#question_' + i + ' input').val()) + '"';
//                        alert($('#question_' + i + ' input').val());
                        break;

                    case 'judge':
                        if (true == $('#question_' + i + ' input').prop('checked')){
//                            alert('true');
                            json += '"' + i + '" : "1"';
                        } else {
//                            alert('false');
                            json += '"' + i + '" : "0"';
                        }
//                        alert($('#question_' + i + ' input').prop('checked'));
                        break;
                }
            }
            json += '}';
            
            if (!fin){
                $.post(
                    '<?= base_url('index.php/test/saveAnswer')?>',
                    {
                        answer_data : json,
                        act_id : '<?= $user_act_data['act_id'] ?>',
                        fin : 0
                    },
                    function (data){
                        var data = JSON.parse(data);
                        switch (data['code']){
                            case 1:
                                //保存成功
                                savetime = new Date().getTime();
                                $("#save_button").popover('toggle');
                                window.setTimeout(function (){
                                    $("#save_button").popover('toggle');
                                }, 2000); 
                                break;
                        }
                    }
                )
            } else {
                $.post(
                    '<?= base_url('index.php/test/saveAnswer')?>',
                    {
                        answer_data : json,
                        act_id : '<?= $user_act_data['act_id'] ?>',
                        fin : 1
                    },
                    function (data){
                        var data = JSON.parse(data); 
                        switch (data['code']){
                            case 1:
                                alert(data['message']);
                                location.reload();
                                break;
                            case -1:
                            case -3:
                                alert(data['error']);
                                location.href = '<?= base_url() ?>';
                                break;
                            case -4:
                            case -5:
                                alert(data['error']);
                                location.reload();
                                break;
                            default:
                                alert(data['error']);
                                break;
                        }
                    }
                )
            }
        }
        <?php endif;?>
    
    </script>
</html>