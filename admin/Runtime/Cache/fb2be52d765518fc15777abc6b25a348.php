<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>登录</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico"> <link href="__PUBLIC__/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="__PUBLIC__/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">

    <link href="__PUBLIC__/css/animate.min.css" rel="stylesheet">
    <link href="__PUBLIC__/css/style.css?v=4.1.0" rel="stylesheet">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <!-- <script>if(window.top !== window.self){ window.top.location = window.location;}</script> -->
</head>

<body class="black-bg" style="background: url(__PUBLIC__/images/admin_bg.jpg)  center top no-repeat";>
<!-- class="middle-box text-center loginscreen  animated fadeInDown"  -->
    <div  class="ibox middle-box text-center loginscreen  animated fadeInDown row wrapper" style="margin-top:130px;max-width:800px !important;width:800px !important">
        <div class="col-sm-6">
            <img  src="__PUBLIC__/images/index_logo.jpg" alt="" style="width:300px;"/>


        </div>

        <!-- <h3>欢迎使用 销费商系统</h3> -->
        <div class="col-sm-6">
        <form class="m-t col-sm-12" role="form" action="__APP__/Login/login" method="post" style="margin-top:60px;">
            <input type="hidden" class="form-control" required="" name="form_key" value="yes">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="用户名" required="" name="username">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" placeholder="密码" required="" name="password">
            </div>
            <input class="login-txt" id="" name="form_key" size="30" type="hidden" value="yes">
            <input name="utf8" type="hidden" value="✓"><input name="authenticity_token" type="hidden" value="lsdV0Sjl8QbKoPLltIOOwEisIzUxNRUNo2liv+O9xIA=">
            <button type="submit" class="btn btn-primary block full-width m-b">登 录</button>



        </form>

        </div>
    </div>
    <script src="__PUBLIC__/js/jquery.min.js?v=2.1.4"></script>
    <script src="__PUBLIC__/js/bootstrap.min.js?v=3.3.6"></script>
</body>

</html>