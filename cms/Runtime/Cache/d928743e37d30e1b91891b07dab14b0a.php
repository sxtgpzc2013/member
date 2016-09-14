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
    <link href="__PUBLIC__/css/cms_style.css?v=2.2.0" rel="stylesheet">

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
					<li class="<?php if (MODULE_NAME == 'Index' && ACTION_NAME == 'index') {echo 'active bcolor';} ?>">
						<a href="__APP__">首页</a>
					</li>
				</ul>
			</li>
			<li class="<?php if (MODULE_NAME == 'Consumers') {echo 'active';} ?>">
				<a href="index.html"><i class="fa fa-columns"></i> <span class="nav-label">消费商管理</span> <span class="fa arrow"></span></a>
				<ul class="nav nav-second-level">
					<li class="<?php if (MODULE_NAME == 'Consumers' && ACTION_NAME == 'edit_info') {echo 'active';} ?>">
						<a href="__APP__/Consumers/edit_info">修改资料</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Consumers' && ACTION_NAME == 'edit_password') {echo 'active';} ?>">
						<a href="__APP__/Consumers/edit_password">修改密码</a>
					</li>
				</ul>
			</li>

			<li class="<?php if (MODULE_NAME == 'Teams') {echo 'active';} ?>">
				<a href="index.html"><i class="fa fa-envelope"></i> <span class="nav-label">团队管理</span> <span class="fa arrow"></span></a>
				<ul class="nav nav-second-level">
					<li class="<?php if (MODULE_NAME == 'Teams' && ACTION_NAME == 'activate') {echo 'active';} ?>">
						<a href="__APP__/Teams/activate">消费商激活</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Teams' && ACTION_NAME == 'register') {echo 'active';} ?>">
						<a href="__APP__/Teams/register">注册消费商</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Teams' && ACTION_NAME == 'recommend_relation') {echo 'active';} ?>">
						<a href="__APP__/Teams/recommend_relation">推荐关系</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Teams' && ACTION_NAME == 'contact_relation') {echo 'active';} ?>">
						<a href="__APP__/Teams/contact_relation">接点关系</a>
					</li>
				</ul>
			</li>

			<li class="<?php if (MODULE_NAME == 'Bonus' && ACTION_NAME == 'index') {echo 'active';} ?>">
				<a href="index.html"><i class="fa fa-columns"></i> <span class="nav-label">奖金管理</span> <span class="fa arrow"></span></a>
				<ul class="nav nav-second-level">
					<li class="<?php if (MODULE_NAME == 'Bonus' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__/Bonus/index">奖金明细</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Bonus' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__/Bonus/index">财务流水</a>
					</li>
				</ul>
			</li>

			<li class="<?php if (MODULE_NAME == 'Finances' && ACTION_NAME == 'index') {echo 'active';} ?>">
				<a href="index.html"><i class="fa fa-columns"></i> <span class="nav-label">财务管理</span> <span class="fa arrow"></span></a>
				<ul class="nav nav-second-level">
					<li class="<?php if (MODULE_NAME == 'Finances' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__">提现申请</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Finances' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__">提现记录</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Finances' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__">消费商转币</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Finances' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__">币种转换</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Finances' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__">转币记录</a>
					</li>
				</ul>
			</li>

			<li class="<?php if (MODULE_NAME == 'News' && ACTION_NAME == 'index') {echo 'active';} ?>">
				<a href="index.html"><i class="fa fa-columns"></i> <span class="nav-label">新闻公告</span> <span class="fa arrow"></span></a>
				<ul class="nav nav-second-level">
					<li class="<?php if (MODULE_NAME == 'News' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__">新闻资讯</a>
					</li>
				</ul>
			</li>

			<li class="<?php if (MODULE_NAME == 'Products' || MODULE_NAME == 'Carts' || MODULE_NAME == 'Orders' || MODULE_NAME == 'Address') {echo 'active';} ?>">
				<a href="index.html"><i class="fa fa-columns"></i> <span class="nav-label">我的商城</span> <span class="fa arrow"></span></a>
				<ul class="nav nav-second-level">
					<li class="<?php if (MODULE_NAME == 'Products' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__/Products/index">商品列表</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Carts' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__/Carts/index">我的购物车</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Orders' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__/Orders/index">订单管理</a>
					</li>
					<li class="<?php if (MODULE_NAME == 'Address' && ACTION_NAME == 'index') {echo 'active';} ?>">
						<a href="__APP__/Address/index">地址管理</a>
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

        <div id="page-wrapper" class="gray-bg dashbard-1">
            <div class="row border-bottom">
				<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
    <ul class="nav navbar-top-links navbar-right">
        <li>
            <span class="m-r-sm text-muted welcome-message"  style="color:#000000 !important;"><a href="__URL__" title="返回首页" style="color:#000000 !important;"><i class="fa fa-home"></i></a>欢迎管理后台</span>
        </li>
        <li>
            <a href="login.html"  style="color:#000000 !important;">
                <i class="fa fa-sign-out"></i> 退出
            </a>
        </li>
    </ul>

</nav>

            </div>

<div class="row wrapper wrapper-content border-bottom white-bg page-heading" style="border-bottom:3px solid #f1c703 !important">
	<div class="col-lg-10"  style="padding:0px;">
		<ol class="breadcrumb">
			<a href="__URL__"><i class="fa fa-home"></i></a>
            <li>
                <a href="__URL__">首页</a>
            </li>
			<li>
                <a>新闻公告</a>
            </li>
            <li>
                <strong>新闻资讯</strong>
            </li>
        </ol>
	</div>
</div>
<div class="wrapper wrapper-content">
	<div class="row">
		<div class="col-lg-12">
			<div class="row">
				<div class="col-lg-12">

					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5 style="float: none !important;">新闻资讯</h5>
						</div>
						<div class="ibox-content">
							<div class="feed-activity-list">

								<div class="feed-element">
									<div>
										<small class="pull-right text-navy">1月前</small>
										<i class="fa fa-volume-up"></i>&nbsp;<strong>井幽幽</strong>
										<div>有人说：“一辈子很长，要跟一个有趣的人在一起”。我想关注我的人，应该是那种喜欢找乐子也乐意分享乐趣的人，你们一定挺优秀的。所以单身的应该在这条留言，互相勾搭一下。特别有钱人又帅可以直接私信我！</div>
																			</div>
								</div>

								<div class="feed-element">
									<div>
										<small class="pull-right">2月前</small>
										<i class="fa fa-volume-up"></i>&nbsp;<strong>马伯庸 </strong>
										<div>又方便，又防水，手感又好，还可以用手机遥控。简直是拍戏利器，由其是跟老师们搭戏的时候…想想还有点小激动啊，嘿嘿。</div>

									</div>
								</div>

								<div class="feed-element">
									<div>
										<small class="pull-right">5月前</small>
										<i class="fa fa-volume-up"></i>&nbsp;<strong>芒果宓 </strong>
										<div>一个完整的梦。</div>

									</div>
								</div>

								<div class="feed-element">
									<div>
										<small class="pull-right">5月前</small>
										<i class="fa fa-volume-up"></i>&nbsp;<strong>刺猬尼克索</strong>
										<div>哈哈哈哈 你卖什么萌啊! 蠢死了</div>

									</div>
								</div>


								<div class="feed-element">
									<div>
										<small class="pull-right">5月前</small>
										<i class="fa fa-volume-up"></i>&nbsp;<strong>老刀99</strong>
										<div>昨天评论里你见过最“温暖和感人”的诗句，整理其中经典100首，值得你收下学习。</div>

									</div>
								</div>
								<div class="feed-element">
									<div>
										<small class="pull-right">5月前</small>
										<i class="fa fa-volume-up"></i>&nbsp;<strong>娱乐小主 </strong>
										<div>你是否想过记录自己的梦？你是否想过有自己的一个记梦本？小时候写日记，没得写了就写昨晚的梦，后来变成了习惯………翻了一晚上自己做过的梦，想哭，想笑…</div>

									</div>
								</div>
								<div class="feed-element">
									<div>
										<small class="pull-right">5月前</small>
										<i class="fa fa-volume-up"></i>&nbsp;<strong>DMG电影 </strong>
										<div>《和外国男票乘地铁，被中国大妈骂不要脸》妹子实在委屈到不行，中国妹子找外国男友很令人不能接受吗？大家都来说说自己的看法</div>

									</div>
								</div>

							</div>
						</div>
					</div>
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