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
	class AdminsAction extends CommonAction
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

			$this -> model = D('Admins');
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

    				'table_name' => 'admins',

    				'where' => "is_del = 0",

    				'order' => 'created_at asc'
    			);

    			$result['admins'] = $this -> model -> easy_select($params, 'no');

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

	    	//查询相关权限信息
	    	$params = array(

	    		'table_name' => 'auth',

	    		'where' => "is_del = 0"
	    	);

	    	$result['auth'] = $this -> model -> easy_select($params);

	    	$form_key = htmlspecialchars($_POST['form_key']);

	    	if ($form_key == 'yes')
	    	{
	    		$data['password'] = $_POST['password'] ? $_POST['password'] : $this -> _back('请添加');

	    		$data['mobile'] = htmlspecialchars($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : $this -> _back('请输入正确的手机号');

	    		//验证手机号是否存在
	    		$check_result = $this -> _check_mobile($data['mobile']);

	    		if (!$check_result)
	    		{
	    			$this -> _back('账号已存在');
	    		}

	    		$data['password'] = md5(md5($data['password']));

	    		$data['name'] = htmlspecialchars($_POST['username']);

	    		$data['type_str'] = $_POST['type_str'] ? $_POST['type_str'] : $this -> _back('请添加管理员权限');

	    		$data['created_at'] = time();

	    		$data['updated_at'] = time();

	    		$data['is_del'] = 0;

	    		//写入数据库
	    		$params = array(

	    			'table_name' => 'admins',

	    			'data' => $data
	    		);

	    		$admin_add = $this -> model -> my_add($params);

	    		if ($admin_add)
	    		{
	    			redirect(__APP__.'/Admins/index', 0);
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
			//判断权限
	    	if ($_SESSION['Rongzi']['admin']['type_str'] != 'super')
	    	{
	    		$this -> _back('权限不足');
	    	}

	    	//查询相关权限信息
	    	$params = array(

	    		'table_name' => 'auth',

	    		'where' => "is_del = 0"
	    	);

	    	$result['auth'] = $this -> model -> easy_select($params);

			$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
			//查看管理数据

	    	$form_key = htmlspecialchars($_POST['form_key']);

	    	if ($form_key == 'yes')
	    	{
	    		//$data['password'] = $_POST['password'] ? $_POST['password'] : $this -> _back('请添加密码');

	    		$data['mobile'] = htmlspecialchars($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : $this -> _back('请输入正确的手机号');

				if($_POST['password']){
		    		$data['password'] = md5(md5($_POST['password']));
				}

	    		$data['name'] = htmlspecialchars($_POST['username']);

	    		$data['type_str'] = $_POST['type_str'] ? $_POST['type_str'] : $this -> _back('请添加管理员权限');

	    		$data['updated_at'] = time();

	    		//写入数据库
	    		$params = array(

	    			'table_name' => 'admins',

					'where' => 'id = '. $id,

	    			'data' => $data
	    		);

	    		$admin_save = $this -> model -> my_save($params);

	    		if ($admin_save)
	    		{
	    			redirect(__APP__.'/Admins/index', 0);
	    		}
	    		else
	    		{
	    			$this -> _back('保存失败，请重试。');
	    		}
	    	}

			//写入数据库
			$params = array(

				'table_name' => 'admins',

				'where' => 'id = '. $id
			);

			$result['admin'] = $this -> model -> my_find($params);

	    	$this -> assign('result', $result);

	    	$this -> display();
	    }

	    /**
		 * 修改密码
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function reset_password()
	    {
	    	$form_key = htmlspecialchars($_POST['form_key']);

	    	if ($form_key == 'yes')
	    	{
	    		$id = intval($_SESSION['Rongzi']['admin']['id']);

	    		if (!$id) { $this -> _back('非法操作'); }

	    		//查询管理员
	    		$params = array(

	    			'table_name' => 'admins',

	    			'where' => "id = {$id} AND is_del = 0"
	    		);

	    		$admin_find = $this -> model -> my_find($params);

	    		$new_password = md5(md5(htmlspecialchars($_POST['new_password'])));

	    		$re_password = md5(md5(htmlspecialchars($_POST['re_password'])));

	    		$old_password = md5(md5(htmlspecialchars($_POST['old_password'])));

	    		if ($old_password == $admin_find['password'] && $new_password && $new_password == $re_password)
	    		{
	    			$data['password'] = $new_password;

	    			$data['updated_at'] = time();

	    			//保存
	    			$params = array(

	    				'table_name' => 'admins',

	    				'where' => "id = {$id}",

	    				'data' => $data
	    			);

	    			$admin_save = $this -> model -> my_save($params);

	    			if ($admin_save == 1)
	    			{
	    				redirect(__APP__.'/Login/login', 0);
	    			}
	    			else
	    			{
	    				$this -> _back('保存失败，请重试。');
	    			}
	    		}
	    		else
	    		{
	    			$this -> _back('密码输入有误');
	    		}
	    	}

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

	    	$data['updated_at'] = time();

	    	$params = array(

	    		'table_name' => 'admins',

	    		'where' => "id = {$id}",

	    		'data' => $data
	    	);

	    	$admin_save = $this -> model -> my_save($params);

	    	if ($admin_save == 1)
	    	{
	    		redirect(__APP__.'/Admins/index', 0);
	    	}
	    	else
	    	{
	    		$this -> _back('删除失败，请重试。');
	    	}
	    }

	    /**
		 * 检查用户名是否存在
		 *
		 * 参数描述：
		 *   mobile 手机号
		 *
		 *
		 * 返回值：
		 *   true/false
		 */
	    public function _check_mobile($mobile)
	    {
	    	//查询用户名
	    	$params = array(

	    		'table_name' => 'admins',

	    		'where' => "is_del = 0 AND mobile = '{$mobile}'"
	    	);

	    	$admin_find = $this -> model -> my_find($params);

	    	if ($admin_find)
	    	{
	    		return false;
	    	}
	    	else
	    	{
	    		return true;
	    	}
	    }

	}
