<?php if (!defined('THINK_PATH')) exit();//判断是否加载thinkphp,如果否则退出
/*********文件描述*********
 * @last update 2014-06-12
 * @alter 张睿
 * @version 1.0.0
 *
 * 功能简介：商户后台登陆控制器类
 * @author 张睿
 * @copyright
 * @time 2014-06-12
 * @version 1.0.0
 */
	class LoginAction extends Action {

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
			$this -> display();
	    }

	    /**
		 * 登陆操作
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
	    	$username = $_POST['username'];

	    	$password = md5(md5($_POST['password']));

	    	$params = array(

	    		'table_name' => 'corps',

	    		'where' => "mobile = '{$username}' AND password = '{$password}' AND is_del = 0 AND status = 2"
	    	);

	    	$corp = $this -> model -> my_find($params);

	    	if ($corp)
	    	{
	    		$_SESSION['Rongzi']['user'] = $corp;

	    		$data['last_login_time'] = time();

	    		$params = array(

	    			'table_name' => 'corps',

	    			'where' => "id = {$corp['id']} AND is_close = 0",

	    			'data' => $data
	    		);

	    		$corp_save = $this -> model -> my_save($params);

	    		redirect(__APP__."/Index/index", 0);
	    	}
	    	else
	    	{
	    		$this -> _back('登陆失败，请重试。');
	    	}
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
	    	unset($_SESSION['OftenGo']['user']);

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
