<!DOCTYPE html>  
<html>  
<head>  
    <title></title>     
    <link href="http://nws.oss-cn-qingdao.aliyuncs.com/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <br/>
    <table class="table table-hover">
        <thead>
            <tr>
                <th colspan="2">活动</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>活动总数</th>
                <td><?= $statis['act_info']['sum']?></td>
            </tr>
            <tr>
                <th>正在进行的活动</th>
                <td><?= $statis['act_info']['progress_sum']?></td>
            </tr>
            <tr>
                <th>过期的活动</th>
                <td><?= $statis['act_info']['overdue_sum']?></td>
            </tr>
            <tr>
                <th>未来开始的活动</th>
                <td><?= $statis['act_info']['future_sum']?></td>
            </tr>
            <tr>
                <th>memcache缓存活动</th>
                <td><?= $statis['act_info']['memcache_act_sum']?></td>
            </tr>
            <tr>
                <th>参加总人数</th>
                <td><?= $statis['act_info']['join_sum'] ?></td>
            </tr>
            <tr class="danger">
                <th colspan="2">各项情况</th>
            </tr>
            <?php foreach ($statis['act_info']['list'] as $value): ?>
            <tr>
                <th colspan="2" class="success"><?= $value['name'] ?></th>
            </tr>
            <tr>
                <th>参加人数</th>
                <td><?= $value['join'] ?></td>
            </tr>
            <tr>
                <th>最高分数</th>
                <td><?= $value['score']['result'][0]['max_score'] ?></td>
            </tr>
            <tr>
                <th>最低分数</th>
                <td><?= $value['score']['result'][0]['min_score'] ?></td>
            </tr>
            <tr>
                <th>平均分数</th>
                <td><?= number_format($value['score']['result'][0]['average_score'], 2) ?></td>
            </tr>
            <tr>
                <th>平均耗时</th>
                <td><?= number_format($value['score']['result'][0]['average_time'] / 60, 2) ?></td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    <hr>
    <table class="table table-hover">
        <thead>
            <tr>
                <th colspan="2">问题</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>问题数量</th>
                <td><?= $statis['question_info']['sum']?></td>
            </tr>
            <tr class="danger">
                <th colspan="2">各类型数量</th>
            </tr>
            <?php foreach ($statis['question_info']['type_sum'] as $key => $value): ?>
            <tr>
                <th><?= $key ?></th>
                <td><?= $value ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <hr>
    <table class="table table-hover">
        <thead>
            <tr>
                <th colspan="2">用户</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>用户数量</th>
                <td><?= $statis['school_info']['sum']?></td>
            </tr>
            <tr  class="danger">
                <th colspan="2">各学校分布</th>
            </tr>
            <?php foreach ($statis['school_info']['type_sum'] as $key => $value): ?>
            <tr>
                <th><?= $key ?></th>
                <td><?= $value ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>