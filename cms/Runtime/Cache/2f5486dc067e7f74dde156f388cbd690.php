<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
	<head>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-touch-fullscreen" content="yes">
	<link rel="apple-touch-icon" href="icon.png" >

	<link rel="stylesheet" type="text/css" href="__PUBLIC__/cms/css/reserve_style.css">
		<link rel="stylesheet" type="text/css" href="__PUBLIC__/cms/css/all.css?time=<?php echo time(); ?>">
		<link rel="stylesheet" type="text/css" href="__PUBLIC__/cms/css/jquery-ui.css?time=<?php echo time(); ?>">
		<link rel="stylesheet" type="text/css" href="__PUBLIC__/cms/css/jquery-ui.pack.css?time=<?php echo time(); ?>">
		<link rel="stylesheet" type="text/css" href="__PUBLIC__/cms/css/style_1.css?time=<?php echo time(); ?>">
	<!-- 	<link rel="stylesheet" type="text/css" href="__PUBLIC__/cms/css/bootstrap.min.css">  -->
		<script type="text/javascript" src="__PUBLIC__/cms/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="__PUBLIC__/cms/js/jquery.min.js"></script>
		<script type="text/javascript" src="__PUBLIC__/cms/js/jquery.ui.js"></script>
		<script type="text/javascript" src="__PUBLIC__/cms/js/ueditor/editor_config.js"></script>
		<script type="text/javascript" src="__PUBLIC__/cms/js/ueditor/editor_all.js"></script>
		<!-- 图表js -->
		<script src="__PUBLIC__/cms/js/Chart.min.js"></script>
		<script language="javascript" type="text/javascript" src="__PUBLIC__/cms/js/My97DatePicker/WdatePicker.js"></script>
	
		<!--[if lte IE 8]>
			<script src="/javascripts/excanvas.js"></script>
		<![endif]-->
	</head>
	<body>
<?php if(!empty($_SESSION['OftenGo']['user'])): ?><div class="header">
			<h1 class="logo" style="text-align:center;">
					社区送
				</h1>
			<ul class="a-list">
			<li><a  class="active"  href="">商户管理系统</a></li>
			<!-- <li><a  href="__APP__/Index/wx_login">微信平台</a></li> -->
			<div class="clearb"></div>
			</ul>
			<div class="user-list">
				<div id="user-list-click">
					<img class="user-img" height="36" width="36" src="__ROOT__/Uploads/images/corps/s_<?php echo ($_SESSION['OftenGo']['user']['logo']); ?>"><span class="text-user-name"><?php echo ($_SESSION['OftenGo']['user']['name']); ?></span><span class="jiantou">▼</span>
				</div>
					<ul id="user-list-con">  
						<li><a href="__APP__/Login/logout">退出</a> </li>
					</ul>
			</div>
			<span class="phone-400">400-019-2098</span>
	</div>

	<script type="text/javascript">
		$(function(){
			$('.user-list').hover(function() {
				$('#user-list-con').stop(true,true).slideDown();
			}, function() {
				$('#user-list-con').stop(true,true).slideUp();
			});
		})
		$(function(){
			var $check = $('.content_table .table-check');
			for (var i = $check.length - 1; i >= 0; i--) {
				$check[i].index = i;
			};
			$check.click(function(){
				if(this.index == 0){
					$check.attr("checked",this.checked);
				}else{
					function fn1 (){
						for (var i = 0; i < $check.length; i++) {
							if((i != 0)&&(!$check[i].checked))return;
						};
						$check[0].checked = true;
					}
					function fn2 (){
						$check[0].checked = false;
					}
					!!this.checked?fn1():fn2();
				}
			})
		})
		$(function (){
			var $div = $('#add-group');
			var $a = $('#add-group-a');
			$a.click(function (){
				$div.show(100);
				return false;
			})
		})
		$(function(){
			$hoverDd = $('.houtai-new-box-2 dd');
			$hoverDd.hover(function() {
				$(this).find('.bianji').show();
				$(this).find('.btn-del').show();
			}, function() {
				$(this).find('.bianji').hide();
				$(this).find('.btn-del').hide();
			});
		})
	</script><?php endif; ?>
		<div class="content-middle">
			<?php if(!empty($_SESSION['OftenGo']['user'])): ?><div class="content-middle">
<div class="con-list">
	<dl>
		<dt>
			客户管理
		</dt>
        <dd class="<?php if (MODULE_NAME == 'Index') {echo 'active';} ?>">
			<a href="__APP__/Index/index">主控制台</a>
		</dd>
		<dd class="<?php if (MODULE_NAME == 'Products') {echo 'active';} ?>">
			<a href="__APP__/Products/index" >商品管理</a>
		</dd>
		<dd class="<?php if (MODULE_NAME == 'ProductSorts') {echo 'active';} ?>">
			<a href="__APP__/ProductSorts/index" >商品分类</a>
		</dd>
		<dd class="<?php if (MODULE_NAME == 'Products') {echo 'active';} ?>">
			<a href="__APP__/Orders/index" >订单管理</a>
		</dd>
		
	</dl>
	
	<div class="line"></div>
	<dl>

		<dt>
			设置
		</dt>
        <dd class="<?php if (MODULE_NAME == '') {echo 'active';} ?>">
			<a href="__APP__/Set/edit_password">修改密码</a>
		</dd>
		<dd class="<?php if (MODULE_NAME == 'Members') {echo 'active';} ?>">
			<a href="__APP__/Members/List_Members" >退出</a>
		</dd>
	</dl>
</div><?php endif; ?>
			<div class="con-right">
<div class="content-tab">
	<ul class="tab_menu">


		<li class="<?php if (MODULE_NAME == 'Set' && ACTION_NAME == 'info') {echo 'current';} ?>">
			<a href="__APP__/Set/info">账户设置</a>
		</li>
		<li class="<?php if (MODULE_NAME == 'Set' && ACTION_NAME == 'edit_password') {echo 'current';} ?>">
			<a href="__APP__/Set/edit_password">修改密码</a>
		</li>
		
	</ul>
</div>

<form action="" method="post" enctype="multipart/form-data">
	<div class="widget-box">
						
		<div class="widget-main">

			<table class="table_form">
				<tbody>
					<tr >
						<td >登陆用户名：</td>
						<td> 
							<?php echo ($_SESSION['OftenGo']['user']['mobile']); ?>
						</td>
					</tr>
					<tr >
						<td >当前密码：</td>
						<td> 
							<input type="hidden" name="form_key" value="yes" />
							<input type="password" name="old_password" />
						</td>
					</tr>
					<tr >
						<td >新密码：</td>
						<td> 
							<input type="password" name="new_password" />
						</td>
					</tr>
					<tr >
						<td >再次输入：</td>
						<td> 
							<input type="password" name="new_password_again" />
						</td>
					</tr>
					<tr >
						<td ></td>
						<td>
							<input class="widget-submit" type="submit" value="提交">
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</form>


			</div>

			<div class="clearb"></div>
		</div>

	</body>
</html>