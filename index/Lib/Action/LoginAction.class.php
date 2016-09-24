<?php if (!defined('THINK_PATH')) exit();//判断是否加载thinkphp,如果否则退出
/*********文件描述*********
 * @last update 2014-06-12
 * @alter  
 * @version 1.0.0
 *
 * 功能简介：商户后台登陆控制器类
 * @author  
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
			$form_key = htmlspecialchars($_POST['form_key']);

			if ($form_key == 'yes')
			{
				$mobile = $_POST['mobile'];

		    	$password = md5(md5($_POST['password']));

		    	$params = array(

		    		'table_name' => 'member',

		    		'where' => "mobile = '{$mobile}' AND psd1 = '{$password}' AND status = 1"
		    	);

		    	$member = $this -> model -> my_find($params);

		    	if ($member)
		    	{
		    		$_SESSION['Rongzi']['user'] = $member;

		    		$data['last_time'] = time();

		    		$params = array(

		    			'table_name' => 'member',

		    			'where' => "uid = {$member['uid']} AND status = 1",

		    			'data' => $data
		    		);

		    		$member_save = $this -> model -> my_save($params);

		    		redirect(__APP__."/Index/index", 0);
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
	    public function out()
	    {
	    	unset($_SESSION['Rongzi']['user']);

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
