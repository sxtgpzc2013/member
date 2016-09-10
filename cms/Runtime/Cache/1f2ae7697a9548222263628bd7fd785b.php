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
			<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.4"></script>
<script src="http://api.map.baidu.com/getscript?v=1.4&amp;ak=&amp;services=&amp;t=20131226072116" type="text/javascript"></script>

<script src="http://api.map.baidu.com/library/MarkerTool/1.2/src/MarkerTool_min.js" type="text/javascript"></script>
<div class="content-tab">
	<ul class="tab_menu">
		<li class="current">
			<a href="">信息设置</a>
		</li>
	</ul>
</div>

<form action="__APP__/Set/info" method="post" enctype="multipart/form-data">
	<input type="hidden" name="form_key" value="yes">
	<input type="hidden" name="id" value="<?php echo ($result['corp_find']['id']); ?>">
	 <input id="site_longitude" name="longitude" type="hidden" value="<?php echo ($result['corp_find']['lng']); ?>">
	 <input id="site_latitude" name="latitude" type="hidden" value="<?php echo ($result['corp_find']['lat']); ?>"> 
	<div class="widget-box"> 
		<div class="widget-main">
			<table class="table_form">
				<tbody>
			<tr>
				<td><b>*</b>商户名称：</td>
				<td> <input id="name" name="name" size="30" type="text" value="<?php echo ($result['corp_find']['name']); ?>">
				<div class="error">
					
				</div></td>
			</tr>
			<tr>
				<td><b>*</b>联系人/经营者姓名：</td>
				<td> <input id="_corp_name" name="contact" size="30" type="text" value="<?php echo ($result['corp_find']['contact']); ?>">
				<div class="error">
					
				</div></td>
			</tr>

			<tr>
				<td><b>*</b>营业执照注册号：</td>
				<td> <input id="" name="corp_register_number" size="30" type="text" value="<?php echo ($result['corp_find']['corp_register_number']); ?>">
				<div class="error">
					
				</div></td>
			</tr>
			<tr>
				<td><b>*</b>送货范围：</td>
				<td> <input id="" name="cell_scope" size="30" type="text" value="<?php echo ($result['corp_find']['cell_scope']); ?>">
				<div class="error">
					
				</div></td>
			</tr>
			<tr>
				<td><b>*</b>送货时间：</td>
				<td> <select name="cell_time">
					<option value="15" <?php if($result['corp_find']['cell_time'] == 15){echo 'selected="selected"';}?>>15分钟</option>
					<option value="30" <?php if($result['corp_find']['cell_time'] == 30){echo 'selected="selected"';}?>>30分钟</option>
					<option value="45" <?php if($result['corp_find']['cell_time'] == 45){echo 'selected="selected"';}?>>45分钟</option>
					<option value="60" <?php if($result['corp_find']['cell_time'] == 60){echo 'selected="selected"';}?>>60分钟</option>
				</select>
				<div class="error">
					
				</div></td>
			</tr>
			<tr>
				<td><b>*</b>营业时间：</td>
				<td> 
					开始时间:<input id="" name="started_at" size="30" type="text" value="<?php echo ($result['corp_find']['started_at']); ?>" placeholder="例如10:00" style="width:100px;">&nbsp;结束时间:<input id="" name="stoped_at" size="30" type="text" value="<?php echo ($result['corp_find']['stoped_at']); ?>" placeholder="例如20:00" style="width:100px;">
				<div class="error">
					
				</div></td>
			</tr>
			<tr>
				<td><b>*</b>起送价：</td>
				<td> <input id="" name="is_cell" size="30" type="text" value="<?php echo ($result['corp_find']['is_cell']); ?>">
				<div class="error">
					
				</div></td>
			</tr>
			<tr>
				<td><b>*</b>送货费：</td>
				<td> <input id="" name="cell_money" size="30" type="text" value="<?php echo ($result['corp_find']['cell_money']); ?>">
				<div class="error">
					
				</div></td>
			</tr>
			<tr>
				<td><b>*</b>商户logo：</td>
				<td> <input id="_banner_logo" name="logo" type="file">
						<br>
						<!-- <a href="/upload/images/site/000/000/006/original/e6a477e0-a53d-45cd-a18e-5dfdbbea17821393469521.jpg" target="_blank"> -->
							 <img style="width:500px;height:300px;" alt="E6a477e0-a53d-45cd-a18e-5dfdbbea17821393469521" src="__ROOT__/Uploads/images/corps/m_<?php echo ($result['corp_find']['logo']); ?>">
						</a>
				<div class="error">
					
				</div></td>
			</tr>
			<tr>
				<td>商户简介：</td>
				<td> <textarea cols="40" id="_content" name="content" rows="20"><?php echo ($result['corp_find']['content']); ?></textarea>
				<div class="error">
					
				</div></td>
			</tr>
			<tr>
				<td>店铺公告：</td>
				<td> <textarea cols="40" id="_content" name="corp_bulletin" rows="20"><?php echo ($result['corp_find']['corp_bulletin']); ?></textarea>
				<div class="error">
					
				</div></td>
			</tr>
					<tr>
						<td><b>*</b>一键拨号：</td>
						<td> <input id="tel" name="tel" size="30" type="text" value="<?php echo ($result['corp_find']['tel']); ?>"> (可以拨通的电话号码)
						<div class="error">
							
						</div></td>
					</tr>
				<!-- 	<tr>
						<td><b></b>一键拨号2：</td>
						<td> <input id="site_tel2" name="site[tel2]" size="30" type="text" value="2147483647"> (本店介绍电话2)
						<div class="error">
							
						</div></td>
					</tr> -->
					<tr>
						<td><b>*</b>一键导航：</td>
						<td> 城市 <input id="site_city_name" name="city_name" size="30" style="width:50px;" type="text" value="<?php echo ($result['corp_find']['city_name']); ?>"> 地址 <input id="site_address" name="address" size="30" type="text" value="<?php echo ($result['corp_find']['address']); ?>"> <a id="search_button" style="color:#006699;" href="javascript:void(0)" class="form-btn">在地图上查找</a>
						
						<div class="error">
							
						</div></td>
					</tr>
	 		<tr>
						<td></td><td>
					<a id="addMarker" style="color:#006699;" href="javascript:void(0)" class="form-btn"  >标注地图</a>
			 在地图上标注您的位置 <br/>
			<div class="map_container" style="width:700px;height:400px;" id="baidumap"></div>
			
						</td>
					</tr>   
				<tr>
						<td></td>
						<td>
						<input class="widget-submit" type="submit" value="提交">
						</td>
					</tr>
				</tbody>
			</table>
			
		</div>
	</div>
	 


<script type="text/javascript">
	(function() {
		var lng = document.getElementById("site_longitude");
		var lat = document.getElementById("site_latitude");
		var city = document.getElementById("site_city_name");
		var address = document.getElementById("site_address");
		var map = new BMap.Map("baidumap");
		var marker;
		var longitude = lng.value;
		var latitude = lat.value;
		if (longitude && latitude) {
			map.centerAndZoom(new BMap.Point(longitude, latitude), 12);
			marker = new BMap.Marker(new BMap.Point(longitude, latitude));
			map.addOverlay(marker);
		} else {
			var localcity = new BMap.LocalCity();
			localcity.get(function(e) {
				map.centerAndZoom(e.center, 12);
			});
		}
		map.addControl(new BMap.NavigationControl());
		map.enableScrollWheelZoom();
		$('#search_button').on('click', function() {
			if (city.value == '' || address.value == '') {
				alert("请填写城市和地址");
			} else {
				myGeo.getPoint(address.value, function(point) {
					if (point) {
						map.centerAndZoom(point, 16);
						marker = new BMap.Marker(point);
						map.addOverlay(marker);
						marker.enableDragging();
						lng.value = point.lng;
						lat.value = point.lat;
						marker.addEventListener("dragend", function(e) {
							lng.value = e.point.lng;
							lat.value = e.point.lat;
							
						});
					}
				}, city.value);
			}
		})
		var myGeo = new BMap.Geocoder();
		var mkrTool = new BMapLib.MarkerTool(map, {
			autoClose : true,
			followText : "标注您的位置"
		});
		$('#addMarker').on('click', function() {
			mkrTool.open();
			map.removeOverlay(marker);
			marker.dispose();
			
			lng.value = "";
			lat.value = "";
		})
		mkrTool.addEventListener("markend", function(e) {
			marker = e.marker;
			
			lng.value = e.marker.getPosition().lng;
			lat.value = e.marker.getPosition().lat;
			marker.enableDragging();
			marker.addEventListener("dragend", function(e) {
				lng.value = e.point.lng;
				
				lat.value = e.point.lat;
			})
		});
	})(); 
</script>
</form>

			</div>

			<div class="clearb"></div>
		</div>

	</body>
</html>