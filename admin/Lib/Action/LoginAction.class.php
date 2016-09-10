<?php if (!defined('THINK_PATH')) exit();//判断是否加载thinkphp,如果否则退出
/*********文件描述*********
 * @last update 2014-6-16
 * @alter
 * @version 1.0.0
 *
 * 功能简介：登陆管理控制器类
 * @author 张睿
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
		public function login_do()
		{
			$form_key = htmlspecialchars($_POST['form_key']);

			if ($form_key == 'yes')
			{
				$username = htmlspecialchars($_POST['username']);

				$password = md5(md5(htmlspecialchars($_POST['password'])));

				//查询
				$params = array(

					'table_name' => 'admins',

					'where' => "mobile = '{$username}' AND password = '{$password}' AND is_del = 0"
				);

				$admin_find = $this -> model -> my_find($params);



				if ($admin_find)
				{
					$_SESSION['Rongzi']['admin'] = $admin_find;

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
	}

?>
