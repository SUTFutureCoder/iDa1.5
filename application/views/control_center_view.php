<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>管理员控制面板</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('/jq-ui/themes/cupertino/easyui.css')?>" id="swicth-style">
<script type="text/javascript" src="<?php echo base_url('/jq-ui/jquery-1.7.2.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('/jq-ui/jquery.easyui.min.js')?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('/jq-ui/style.css')?>" id="swicth-style">
</head> 
<body class="easyui-layout">
<div region="north" border="false" class="cs-north" style="height:30px; overflow:hidden">
                <div  style="height: 30px; top:5px; overflow: hidden; position: relative; left: 10px; float: left">
                </div>
		<div class="cs-north-bg"style="top:0%" >
		<ul class="ui-skin-nav">				
			<li class="li-skinitem" title="gray"><span class="gray" rel="gray"></span></li>
			<li class="li-skinitem" title="pepper-grinder"><span class="pepper-grinder" rel="pepper-grinder"></span></li>
			<li class="li-skinitem" title="blue"><span class="blue" rel="blue"></span></li>
			<li class="li-skinitem" title="cupertino"><span class="cupertino" rel="cupertino"></span></li>
			<li class="li-skinitem" title="dark-hive"><span class="dark-hive" rel="dark-hive"></span></li>
			<li class="li-skinitem" title="sunny"><span class="sunny" rel="sunny"></span></li>
		</ul>	
		</div>	</div>
	<div region="west" border="true" split="true" title="索引" class="cs-west">
                <div class="easyui-accordion" fit="true" border="false">

                        <div title="题目相关">
                                <a href="javascript:void(0);" src="<?= base_url('index.php/admin_add_question') ?>" class="cs-navi-tab">添加题目</a></p>
                                <a href="javascript:void(0);" src="../index.php/log/getLogPage" class="cs-navi-tab">题目列表</a></p>
                        </div>

                        <div title="答题相关">
                                <a href="javascript:void(0);" src="<?= base_url('index.php/admin_add_act') ?>" class="cs-navi-tab">添加活动</a></p>
                                <a href="javascript:void(0);" src="index.php/changepass" class="cs-navi-tab">活动列表</a></p>
                        </div>      
                    
                        <div title="平台相关">
                                <a href="javascript:void(0);" src="<?= base_url('index.php/admin_statis') ?>" class="cs-navi-tab">统计</a></p>
                        </div>      

                        <div title="权限相关">
                                <a href="javascript:void(0);" src="<?= base_url('index.php/admin_add_user')?>" class="cs-navi-tab">添加用户</a></p>
                                <a href="javascript:void(0);" src="index.php/changepass" class="cs-navi-tab">更改密码</a></p>
                                <a href="javascript:void(0);" src="<?= base_url('index.php/changepass')?>" class="cs-navi-tab">权限管理</a></p>
                        </div>       
		</div>
	</div>
	<div id="mainPanle" region="center" border="true" border="false">
            <div id="tabs" class="easyui-tabs"  fit="true" border="false" >
                <div title="Home">
                    <div class="cs-home-remark">
                        <h1>控制面板</h1> <br>
                        <h2>Shenyang University Of Technology </h2><br/>
                        <a style="color: red">Made In China</a><br/>
                        Copyright 2015.03-<?=  date("Y") . '.' . date('m')?> SUT NWS/ACM<br/> 
                        Powered By *Chen Lin 保留著作权<br/>
                        Alpha1 build 0001<br/>
                    </div>
                </div>
            </div>
	</div>

	<div region="south" border="false" class="cs-south">©沈阳工业大学</div>
	
	<div id="mm" class="easyui-menu cs-tab-menu">
		<div id="mm-tabupdate">刷新</div>
		<div class="menu-sep"></div>
		<div id="mm-tabclose">关闭</div>
		<div id="mm-tabcloseother">关闭其他</div>
		<div id="mm-tabcloseall">关闭全部</div>
	</div>
</body>
</html>