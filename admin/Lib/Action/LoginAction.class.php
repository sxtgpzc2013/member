<?php if (!defined('THINK_PATH')) exit();//判断是否加载thinkphp,如果否则退出
/*********文件描述*********
 * @last update 2014-6-16
 * @alter
 * @version 1.0.0
 *
 * 功能简介：登陆管理控制器类
 * @author
 * @copyright 经常去
 * @time 2014-6-16
 * @version 1.0.0
 */
	class LoginAction extends Action
	{
		/**
		 * 构造方法-实例化MODEL
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
		public function __construct()
		{
			parent::__construct();

			$this -> model = D('Login');
		}

		/**
		 * 登陆页
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
		public function login()
		{
			$form_key = htmlspecialchars($_POST['form_key']);

			if ($form_key == 'yes')
			{
				$username = htmlspecialchars($_POST['username']);

				$password = md5(md5(htmlspecialchars($_POST['password'])));

				//查询
				$params = array(

					'table_name' => 'admins',

					'where' => "mobile = '{$username}' AND password = '{$password}' AND is_del = 0 AND status = 0"
				);

				$admin_find = $this -> model -> my_find($params);

				if ($admin_find)
				{
					$_SESSION['Rongzi']['admin'] = $admin_find;

					$data['login_number'] = $admin_find['login_number'] + 1;

					$data['last_login_time'] = time();

					$data['last_login_ip'] = $this -> get_client_ip();

					//更新相关登录信息
					$params = array(

						'table_name' => 'admins',

						'where' => "id = {$admin_find['id']}",

						'data' => $data
					);

					$my_save = $this -> model -> my_save($params);

					redirect(__APP__.'/Corps/index', 0);
				}
				else
				{
					$this -> _back('登陆失败，请重试。');
				}
			}

			$this -> display();
		}

		/**
		 * 退出登陆
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
		public function logout()
		{
			unset($_SESSION['Rongzi']['admin']);

			unset($_SESSION['Rongzi']['adminauth']);

			redirect(__APP__.'/Login/login', 0);
		}

		/**
		 * 返回
		 *
		 * 参数描述：
		 *   message
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function _back($message)
	    {
	    	$msg = $message ? $message : '出现错误，请稍后再试。';

	    	die('<meta http-equiv="Content-Type" content="text/html"; charset="utf8"><script language="javascript">alert("' . $msg . '");window.history.back(-1);</script>');
	    }


		//获取客户端IP
		function get_client_ip($type = 0) {
		    $type       =  $type ? 1 : 0;
		    static $ip  =   NULL;
		    if ($ip !== NULL) return $ip[$type];
		    if($_SERVER['HTTP_X_REAL_IP']){//nginx 代理模式下，获取客户端真实IP
		        $ip=$_SERVER['HTTP_X_REAL_IP'];
		    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {//客户端的ip
		        $ip     =   $_SERVER['HTTP_CLIENT_IP'];
		    }elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {//浏览当前页面的用户计算机的网关
		        $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
		        $pos    =   array_search('unknown',$arr);
		        if(false !== $pos) unset($arr[$pos]);
		        $ip     =   trim($arr[0]);
		    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
		        $ip     =   $_SERVER['REMOTE_ADDR'];//浏览当前页面的用户计算机的ip地址
		    }else{
		        $ip=$_SERVER['REMOTE_ADDR'];
		    }
		    // IP地址合法验证
		    $long = sprintf("%u",ip2long($ip));
		    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
		    return $ip[$type];
		 }
	}

?>
