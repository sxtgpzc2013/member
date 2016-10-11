<?php if (!defined('THINK_PATH')) exit();//判断是否加载thinkphp,如果否则退出
/*********文件描述*********
 * @last update 2014-08-21
 * @alter
 * @version 1.0.0
 *
 * 功能简介：运营后台管理员控制器类
 * @author
 * @copyright
 * @time 2014-08-21
 * @version 1.0.0
 */
	class AuthAction extends CommonAction
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

			$this -> model = D('Auth');
		}

	    /**
		 * 首页
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function index()
	    {
	    	//查询当前登陆的管理员
	    	$params = array(

	    		'table_name' => 'admins',

	    		'where' => "id = {$_SESSION['Rongzi']['admin']['id']}"
    		);

    		$admin_find = $this -> model -> my_find($params);

    		//更新SESSION
    		$_SESSION['Rongzi']['admin'] = $admin_find;

    		//判断权限
    		if ($admin_find['type_str'] == 'super')
    		{
    			//查询管理员
    			$params = array(

    				'table_name' => 'auth',

    				'where' => "is_del = 0",

    				'order' => 'created_at asc'
    			);

    			$result['auth'] = $this -> model -> easy_select($params, 'no');

    		}
    		else
    		{
    			$this -> _back('权限不够');
    		}

    		$this -> assign('result', $result);

	    	$this -> display();
	    }

	    /**
		 * 新增管理员
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function add()
	    {
	    	//判断权限
	    	if ($_SESSION['Rongzi']['admin']['type_str'] != 'super')
	    	{
	    		$this -> _back('权限不足');
	    	}

	    	$form_key = htmlspecialchars($_POST['form_key']);

	    	if ($form_key == 'yes')
	    	{
	    		$data = $_POST;

	    		//写入数据库
	    		$params = array(

	    			'table_name' => 'auth',

	    			'data' => $data
	    		);

	    		$admin_add = $this -> model -> my_add($params);

	    		if ($admin_add)
	    		{
	    			redirect(__APP__.'/Auth/index', 0);
	    		}
	    		else
	    		{
	    			$this -> _back('保存失败，请重试。');
	    		}
	    	}

	    	$this -> assign('result', $result);

	    	$this -> display();
	    }

	    /**
		 * 编辑管理员
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function edit()
	    {
	    	$form_key = htmlspecialchars($_POST['form_key']);

			$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];

	    	if ($form_key == 'yes')
	    	{
				unset($_POST['id']);

				$data = $_POST;

	    		//写入数据库
	    		$params = array(

	    			'table_name' => 'auth',

	    			'where' => "id = {$id}",

	    			'data' => $data
	    		);

	    		$admin_save = $this -> model -> my_save($params);

	    		if ($admin_save == 1)
	    		{
	    			redirect(__APP__.'/Auth/index', 0);
	    		}
	    		else
	    		{
	    			$this -> _back('保存失败，请重试。');
	    		}
	    	}


			$params = array(

				'table_name' => 'auth',

				'where' => "id = {$id}"
			);

			$auth = $this -> model -> my_find($params);

			$this -> assign('auth', $auth);

	    	$this -> display();
	    }

	    /**
		 * 删除
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function delete()
	    {
	    	//判断权限
	    	if ($_SESSION['Rongzi']['admin']['type_str'] != 'super') { $this -> _back('权限不够'); }

	    	$id = intval($_GET['id']);

	    	if (!$id) { $this -> _back('非法操作'); }

	    	$data['is_del'] = 1;

	    	$params = array(

	    		'table_name' => 'auth',

	    		'where' => "id = {$id}",

	    		'data' => $data
	    	);

	    	$admin_save = $this -> model -> my_save($params);

	    	if ($admin_save == 1)
	    	{
	    		redirect(__APP__.'/Auth/index', 0);
	    	}
	    	else
	    	{
	    		$this -> _back('删除失败，请重试。');
	    	}
	    }
	}
