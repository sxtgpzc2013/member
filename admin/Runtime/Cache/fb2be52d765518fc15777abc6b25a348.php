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
    <script>if(window.top !== window.self){ window.top.location = window.location;}</script>
</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen  animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name">Rz</h1>

            </div>
            <h3>欢迎使用 会员系统</h3>

            <form class="m-t" role="form" action="__APP__/Login/login_do" method="post" >
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="用户名" required="" name="corp_username">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="密码" required="" name="corp_password">
                </div>
                <input class="login-txt" id="" name="form_key" size="30" type="hidden" value="yes">
                <input name="utf8" type="hidden" value="✓"><input name="authenticity_token" type="hidden" value="lsdV0Sjl8QbKoPLltIOOwEisIzUxNRUNo2liv+O9xIA=">
                <button type="submit" class="btn btn-primary block full-width m-b">登 录</button>


                <p class="text-muted text-center"> <a href="login.html#"><small>忘记密码了？</small></a>
                </p>

            </form>
        </div>
    </div>
    <script src="__PUBLIC__/js/jquery.min.js?v=2.1.4"></script>
    <script src="__PUBLIC__/js/bootstrap.min.js?v=3.3.6"></script>
</body>

</html>