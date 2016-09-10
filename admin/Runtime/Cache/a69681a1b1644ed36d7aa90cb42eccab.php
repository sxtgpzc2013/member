<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="renderer" content="webkit">

    <title>管理后台</title>
    <meta name="keywords" content="管理后台">
    <meta name="description" content="管理后台">

    <link href="__PUBLIC__/css/bootstrap.min.css?v=3.4.0" rel="stylesheet">
    <link href="__PUBLIC__/font-awesome/css/font-awesome.css?v=4.3.0" rel="stylesheet">

    <!-- Morris -->
    <link href="__PUBLIC__/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">

    <!-- Gritter -->
    <link href="__PUBLIC__/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">

    <link href="__PUBLIC__/css/animate.css" rel="stylesheet">
    <link href="__PUBLIC__/css/style.css?v=2.2.0" rel="stylesheet">

</head>

<body>
    <div id="wrapper">
    	<nav class="navbar-default navbar-static-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav" id="side-menu">
			<li class="nav-header">

				<div class="logo-element">
					Rongzi
				</div>

			</li>
			<li class="<?php if (MODULE_NAME == 'Index' && ACTION_NAME == 'index') {echo 'active';} ?>">
				<a href="index.html"><i class="fa fa-th-large"></i> <span class="nav-label">首页</span> <span class="fa arrow"></span></a>
				<ul class="nav nav-second-level">
					<li class="<?php if (MODULE_NAME == 'Index' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__">首页</a>
					</li>
				</ul>
			</li>
			<li class="<?php if (MODULE_NAME == 'News' && ACTION_NAME == 'index') {echo 'active';} ?>">
				<a href="index.html"><i class="fa fa-columns"></i> <span class="nav-label">新闻公告</span> <span class="fa arrow"></span></a>
				<ul class="nav nav-second-level">
					<li class="<?php if (MODULE_NAME == 'News' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__">公司新闻</a>
					</li>
					<!-- <li class="<?php if (MODULE_NAME == 'News' && ACTION_NAME == 'add') {echo 'active';} ?>">
						<a href="__APP__">添加新闻</a>
					</li> -->
				</ul>
			</li>

			<li class="<?php if (MODULE_NAME == 'Admins') {echo 'active';} ?>">
				<a href="index.html"><i class="fa fa-columns"></i> <span class="nav-label">管理员列表</span> <span class="fa arrow"></span></a>
				<ul class="nav nav-second-level">
					<li class="<?php if (MODULE_NAME == 'Admins' && ACTION_NAME == 'auth') {echo 'active bcolor';} ?>">
						<a href="__APP__">权限管理</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Admins' && ACTION_NAME == 'add') {echo 'active bcolor';} ?>">
						<a href="__APP__/Admins/add">新增管理员</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Admins' && ACTION_NAME == 'edit') {echo 'active bcolor';} ?>">
						<a href="__APP__/Admins/edit">管理员修改</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Admins' && ACTION_NAME == 'index') {echo 'active bcolor';} ?>">
						<a href="__APP__/Admins/index">管理员列表</a>
					</li>
				</ul>
			</li>

			<li class="<?php if (MODULE_NAME == 'Corps' && ACTION_NAME == 'index') {echo 'active';} ?>">
				<a href="index.html"><i class="fa fa-sitemap"></i> <span class="nav-label">分销商管理</span> <span class="fa arrow"></span></a>
				<ul class="nav nav-second-level">
					<li class="<?php if (MODULE_NAME == 'Corps' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__/Corps/index">分销商列表</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Corps' && ACTION_NAME == 'activate') {echo 'active';} ?>">
						<a href="__APP__/Corps/activate">分销商激活</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Corps' && ACTION_NAME == 'register') {echo 'active';} ?>">
						<a href="__APP__/Corps/register">分销商注册</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Corps' && ACTION_NAME == 'recommend_topology') {echo 'active';} ?>">
						<a href="__APP__/Corps/recommend_topology">推荐拓扑</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Corps' && ACTION_NAME == 'contact_topology') {echo 'active';} ?>">
						<a href="__APP__/Corps/contact_topology">接点拓扑</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Corps' && ACTION_NAME == 'upgrade') {echo 'active';} ?>">
						<a href="__APP__/Corps/upgrade">分销商升级</a>
					</li>
				</ul>
			</li>


			<li class="<?php if (MODULE_NAME == 'Finance' && ACTION_NAME == 'index') {echo 'active';} ?>">
				<a href="index.html"><i class="fa fa-bar-chart-o"></i> <span class="nav-label">财务管理</span> <span class="fa arrow"></span></a>
				<ul class="nav nav-second-level">
					<li class="<?php if (MODULE_NAME == 'Finance' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__">奖金统计</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Finance' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__">业绩统计</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Finance' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__">财务流水</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Finance' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__">转账明细</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Finance' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__">公司充值</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Finance' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__">公司扣币</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Finance' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__">公司财务</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Finance' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__">提现申请</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Finance' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__">提现记录</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Finance' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__">充值记录</a>
					</li>
				</ul>
			</li>

			<li class="<?php if (MODULE_NAME == 'Set' && ACTION_NAME == 'index') {echo 'active';} ?>">
				<a href="index.html"><i class="fa fa-columns"></i> <span class="nav-label">系统设置</span> <span class="fa arrow"></span></a>
				<ul class="nav nav-second-level">
					<li class="<?php if (MODULE_NAME == 'Set' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__">计划任务</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Set' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__">奖金设置</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Set' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__">系统设置</a>
					</li>
				</ul>
			</li>


			<!-- <li>
				<a href="index.html#"><i class="fa fa fa-globe"></i> <span class="nav-label">v2.0新增</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level">
					<li><a href="toastr_notifications.html">Toastr通知</a>
					</li>
					<li><a href="nestable_list.html">嵌套列表</a>
					</li>
					<li><a href="timeline_v2.html">时间轴</a>
					</li>
					<li><a href="forum_main.html">论坛</a>
					</li>
					<li><a href="code_editor.html">代码编辑器</a>
					</li>
					<li><a href="modal_window.html">模态窗口</a>
					</li>
					<li><a href="validation.html">表单验证</a>
					</li>
					<li><a href="tree_view_v2.html">树形视图</a>
					</li>
					<li><a href="chat_view.html">聊天窗口</a>
					</li>
				</ul>
			</li>

			<li>
				<a href="index.html#"><i class="fa fa-bar-chart-o"></i> <span class="nav-label">图表</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level">
					<li><a href="graph_echarts.html">百度ECharts</a>
					</li>
					<li><a href="graph_flot.html">Flot</a>
					</li>
					<li><a href="graph_morris.html">Morris.js</a>
					</li>
					<li><a href="graph_rickshaw.html">Rickshaw</a>
					</li>
					<li><a href="graph_peity.html">Peity</a>
					</li>
					<li><a href="graph_sparkline.html">Sparkline</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="mailbox.html"><i class="fa fa-envelope"></i> <span class="nav-label">信箱 </span><span class="label label-warning pull-right">16</span></a>
				<ul class="nav nav-second-level">
					<li><a href="mailbox.html">收件箱</a>
					</li>
					<li><a href="mail_detail.html">查看邮件</a>
					</li>
					<li><a href="mail_compose.html">写信</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="widgets.html"><i class="fa fa-flask"></i> <span class="nav-label">小工具</span></a>
			</li>
			<li>
				<a href="index.html#"><i class="fa fa-edit"></i> <span class="nav-label">表单</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level">
					<li><a href="form_basic.html">基本表单</a>
					</li>
					<li><a href="form_validate.html">表单验证</a>
					</li>
					<li><a href="form_advanced.html">高级插件</a>
					</li>
					<li><a href="form_wizard.html">步骤条</a>
					</li>
					<li><a href="form_webuploader.html">百度WebUploader</a>
					</li>
					<li><a href="form_file_upload.html">文件上传</a>
					</li>
					<li><a href="form_editors.html">富文本编辑器</a>
					</li>
					<li><a href="form_simditor.html">simditor</a>
					</li>
					<li><a href="form_avatar.html">头像裁剪上传</a>
					</li>
					<li><a href="layerdate.html">日期选择器layerDate</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="index.html#"><i class="fa fa-desktop"></i> <span class="nav-label">页面</span></a>
				<ul class="nav nav-second-level">
					<li><a href="contacts.html">联系人</a>
					</li>
					<li><a href="profile.html">个人资料</a>
					</li>
					<li><a href="projects.html">项目</a>
					</li>
					<li><a href="project_detail.html">项目详情</a>
					</li>
					<li><a href="file_manager.html">文件管理器</a>
					</li>
					<li><a href="calendar.html">日历</a>
					</li>
					<li><a href="faq.html">帮助中心</a>
					</li>
					<li><a href="timeline.html">时间轴</a>
					</li>
					<li><a href="pin_board.html">标签墙</a>
					</li>
					<li><a href="invoice.html">单据</a>
					</li>
					<li><a href="login.html">登录</a>
					</li>
					<li><a href="register.html">注册</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="index.html#"><i class="fa fa-files-o"></i> <span class="nav-label">其他页面</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level">
					<li><a href="search_results.html">搜索结果</a>
					</li>
					<li><a href="lockscreen.html">登录超时</a>
					</li>
					<li><a href="404.html">404页面</a>
					</li>
					<li><a href="500.html">500页面</a>
					</li>
					<li><a href="empty_page.html">空白页</a>
					</li>
				</ul>
			</li>

			<li>
				<a href="index.html#"><i class="fa fa-flask"></i> <span class="nav-label">UI元素</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level">
					<li><a href="typography.html">排版</a>
					</li>
					<li><a href="icons.html">字体图标</a>
					</li>
					<li><a href="iconfont.html">阿里巴巴矢量图标库</a>
					</li>
					<li><a href="draggable_panels.html">拖动面板</a>
					</li>
					<li><a href="buttons.html">按钮</a>
					</li>
					<li><a href="tabs_panels.html">选项卡 & 面板</a>
					</li>
					<li><a href="notifications.html">通知 & 提示</a>
					</li>
					<li><a href="badges_labels.html">徽章，标签，进度条</a>
					</li>
					<li><a href="layer.html">Web弹层组件layer</a>
					</li>
					<li><a href="tree_view.html">树形视图</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="grid_options.html"><i class="fa fa-laptop"></i> <span class="nav-label">栅格</span></a>
			</li>
			<li>
				<a href="index.html#"><i class="fa fa-table"></i> <span class="nav-label">表格</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level">
					<li><a href="table_basic.html">基本表格</a>
					</li>
					<li><a href="table_data_tables.html">数据表格(DataTables)</a>
					</li>
					<li><a href="table_jqgrid.html">jqGrid</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="index.html#"><i class="fa fa-picture-o"></i> <span class="nav-label">图库</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level">
					<li><a href="basic_gallery.html">基本图库</a>
					</li>
					<li><a href="carousel.html">图片切换</a>
					</li>

				</ul>
			</li>
			<li>
				<a href="index.html#"><i class="fa fa-sitemap"></i> <span class="nav-label">菜单 </span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level">
					<li>
						<a href="index.html#">三级菜单 <span class="fa arrow"></span></a>
						<ul class="nav nav-third-level">
							<li>
								<a href="index.html#">三级菜单 01</a>
							</li>
							<li>
								<a href="index.html#">三级菜单 01</a>
							</li>
							<li>
								<a href="index.html#">三级菜单 01</a>
							</li>

						</ul>
					</li>
					<li><a href="index.html#">二级菜单</a>
					</li>
					<li>
						<a href="index.html#">二级菜单</a>
					</li>
					<li>
						<a href="index.html#">二级菜单</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="webim.html"><i class="fa fa-comments"></i> <span class="nav-label">即时通讯</span><span class="label label-danger pull-right">New</span></a>
			</li>
			<li>
				<a href="css_animation.html"><i class="fa fa-magic"></i> <span class="nav-label">CSS动画</span></a>
			</li>
			<li>
				<a href="index.html#"><i class="fa fa-cutlery"></i> <span class="nav-label">工具 </span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level">
					<li><a href="form_builder.html">表单构建器</a>
					</li>
				</ul>
			</li> -->
		</ul>

	</div>
</nav>

        <div id="page-wrapper" class="white-bg dashbard-1">
            <div class="row border-bottom">
				<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
    <ul class="nav navbar-top-links navbar-right" >
        <li >
            <span class="m-r-sm text-muted welcome-message"  style="color:#ffffff !important;">
            <strong class="font-bold"><?php echo $_SESSION['Rongzi']['admin']['name'];?></strong>欢迎管理后台
            <a href="__URL__" title="返回首页"  style="color:#ffffff !important;"><i class="fa fa-home"></i></a></span>
        </li>
        <li>
            <a href="login.html"  style="color:#ffffff !important;">
                <i class="fa fa-sign-out"></i> 退出
            </a>
        </li>
    </ul>

</nav>

            </div>

<link href="__PUBLIC__/css/plugins/iCheck/custom.css" rel="stylesheet">
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<ol class="breadcrumb">
			<a href="__URL__"><i class="fa fa-home"></i></a>
            <li>
                <a href="__URL__">首页</a>
            </li>
            <li>
                <a>新闻公告</a>
            </li>
            <li>
                <strong>公司新闻</strong>
            </li>
        </ol>
	</div>
</div>
<div class="wrapper wrapper-content animated">
	<div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5 class="text-center">新增管理员</h5>

                </div>
                <div class="ibox-content">
					<form class="form-horizontal m-t" id="signupForm">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">*管理员名：</label>
                            <div class="col-sm-6">
                                <input id="username" name="username" class="form-control" type="text" aria-required="true" aria-invalid="true" class="error">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">*密码：</label>
                            <div class="col-sm-6">
                                <input id="password" name="password" class="form-control" type="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">*确认密码：</label>
                            <div class="col-sm-6">
                                <input id="confirm_password" name="confirm_password" class="form-control" type="password">
                            </div>
							<div class="col-sm-3">
								<span class="help-block">(不能小于6位数,且不能为纯数字)</span>
							</div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">*E-mail：</label>
                            <div class="col-sm-6">
                                <input id="email" name="email" class="form-control" type="email">
                            </div>
							<div class="col-sm-3">
								<span class="help-block">(用于找回密码)</span>
							</div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6 col-sm-offset-3">
                                <button class="btn btn-primary" type="submit">提交</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>
<div class="footer">
	<div class="pull-right">
		By：<a href="http://www.zi-han.net" target="_blank">zihan's blog</a>
	</div>
	<div>
		<strong>Copyright</strong> H+ &copy; 2014
	</div>
</div>
</div>
</div>

<!-- Mainly scripts -->
<script src="__PUBLIC__/js/jquery-2.1.1.min.js"></script>
<script src="__PUBLIC__/js/bootstrap.min.js?v=3.4.0"></script>
<script src="__PUBLIC__/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="__PUBLIC__/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Flot -->
<script src="__PUBLIC__/js/plugins/flot/jquery.flot.js"></script>
<script src="__PUBLIC__/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
<script src="__PUBLIC__/js/plugins/flot/jquery.flot.spline.js"></script>
<script src="__PUBLIC__/js/plugins/flot/jquery.flot.resize.js"></script>
<script src="__PUBLIC__/js/plugins/flot/jquery.flot.pie.js"></script>
<script src="__PUBLIC__/js/plugins/flot/jquery.flot.symbol.js"></script>

<!-- Peity -->
<script src="__PUBLIC__/js/plugins/peity/jquery.peity.min.js"></script>
<script src="__PUBLIC__/js/demo/peity-demo.js"></script>

<!-- Custom and plugin javascript -->
<script src="__PUBLIC__/js/hplus.js?v=2.2.0"></script>
<script src="__PUBLIC__/js/plugins/pace/pace.min.js"></script>

<!-- jQuery UI -->
<script src="__PUBLIC__/js/plugins/jquery-ui/jquery-ui.min.js"></script>

<!-- Jvectormap -->
<script src="__PUBLIC__/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="__PUBLIC__/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

<!-- EayPIE -->
<script src="__PUBLIC__/js/plugins/easypiechart/jquery.easypiechart.js"></script>

<!-- Sparkline -->
<script src="__PUBLIC__/js/plugins/sparkline/jquery.sparkline.min.js"></script>

<!-- Sparkline demo data  -->
<script src="__PUBLIC__/js/demo/sparkline-demo.js"></script>
</body>

</html>

<!-- jQuery Validation plugin javascript-->
<script src="__PUBLIC__/js/plugins/validate/jquery.validate.min.js"></script>
<script src="__PUBLIC__/js/plugins/validate/messages_zh.min.js"></script>
<script>
   //以下为修改jQuery Validation插件兼容Bootstrap的方法，没有直接写在插件中是为了便于插件升级
   $.validator.setDefaults({
	   highlight: function (element) {
		   $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
	   },
	   success: function (element) {
		   element.closest('.form-group').removeClass('has-error').addClass('has-success');
	   },
	   errorElement: "span",
	   errorClass: "help-block m-b-none",
	   validClass: "help-block m-b-none"


   });

	//以下为官方示例
   $().ready(function () {
	   // validate signup form on keyup and submit
	   $("#signupForm").validate({
		   rules: {
			   firstname: "required",
			   lastname: "required",
			   username: {
				   required: true
			   },
			   password: {
				   required: true,
				   minlength: 6
			   },
			   confirm_password: {
				   required: true,
				   minlength: 5,
				   equalTo: "#password"
			   },
			   email: {
				   required: true,
				   email: true
			   },
			   topic: {
				   required: "#newsletter:checked",
				   minlength: 2
			   },
			   agree: "required"
		   },
		   messages: {
			   username: {
				   required: "请输入管理员名"
			   },
			   password: {
				   required: "请输入您的密码",
				   minlength: "密码必须6个字符以上"
			   },
			   confirm_password: {
				   required: "请再次输入密码",
				   minlength: "密码必须5个字符以上",
				   equalTo: "两次输入的密码不一致"
			   },
			   email: "请输入您的E-mail",
			   agree: "请输入邮箱"
		   }
	   });
   });
</script>