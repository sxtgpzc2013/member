<?php if (!defined('THINK_PATH')) exit();//判断是否加载thinkphp,如果否则退出
/*********文件描述*********
 * @last
 * @alter
 * @version 1.0.0
 *
 * 功能简介：
 * @author
 * @copyright
 * @time
 * @version 1.0.0
 */
	class AddressAction extends CommonAction {

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

			$this -> model = D('Address');
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
	    	$params = array(

	    		'table_name' => 'user_address',

	    		'where' => "user_id = {$_SESSION['Rongzi']['user']['uid']} AND is_del = 0"
	    	);

	    	$result = $this -> model -> easy_select($params);

	    	$this -> assign('result', $result);

			$this->display();
	    }

	    /**
		 * 新增
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
	    	if (isset($_POST['form_key']) && htmlspecialchars($_POST['form_key']) == 'yes')
	    	{
	    		$data['user_id'] = $_SESSION['Rongzi']['user']['uid'];

	    		$data['name'] = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : $this -> _back('请填写收件人姓名');

	    		$data['mobile'] = isset($_POST['mobile']) && is_tel($_POST['mobile']) ? $_POST['mobile'] : $this -> _back('请填写正确的联系方式');

	    		$data['address'] = isset($_POST['address']) ? htmlspecialchars($_POST['address']) : $this -> _back('请填写详细地址');

	    		$data['area'] = isset($_POST['area']) ? htmlspecialchars($_POST['area']) : $this -> _back('请填写所属地区');

	    		$data['created_at'] = time();

	    		$data['updated_at'] = time();

	    		$data['is_default'] = isset($_POST['is_default']) ? 1 : 0;

	    		$data['is_del'] = 0;

	    		if ($data['is_default'] == 1)
	    		{
	    			$params = array(

	    				'table_name' => 'user_address',

	    				'where' => "user_id = {$_SESSION['Rongzi']['user']['uid']} AND is_del = 0 AND is_default = 1",

	    				'data' => array(

	    					'is_default' => 0,

	    					'updated_at' => time()
	    				)
	    			);

	    			$address_save = $this -> model -> my_save($params);
	    		}

	    		$params = array(

	    			'table_name' => 'user_address',

	    			'data' => $data
	    		);

	    		$user_address_add = $this -> model -> my_add($params);

	    		if ($user_address_add)
	    		{
	    			redirect(__APP__.'/Address/index', 0);
	    		}
	    		else
	    		{
	    			$this -> _back('保存失败 请稍后重试');
	    		}
	    	}

	    	$this -> display();
	    }

	    /**
		 * 编辑
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
	    	$id = isset($_POST['id']) ? intval($_POST['id']) : intval($_GET['id']);

	    	if (!$id)
	    	{
	    		$this -> _back('缺少必要参数');
	    	}

	    	$params = array(

	    		'table_name' => 'user_address',

	    		'where' => "id = {$id} AND is_del = 0 AND user_id = {$_SESSION['Rongzi']['user']['uid']}"
	    	);

	    	$result = $this -> model -> my_find($params);

	    	if (!$result)
	    	{
	    		$this -> _back('没有找到相关项');
	    	}

	    	if (isset($_POST['form_key']) && htmlspecialchars($_POST['form_key']) == 'yes')
	    	{
	    		$data['name'] = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : $this -> _back('请填写收件人姓名');

	    		$data['mobile'] = isset($_POST['mobile']) && is_tel($_POST['mobile']) ? $_POST['mobile'] : $this -> _back('请填写正确的联系方式');

	    		$data['address'] = isset($_POST['address']) ? htmlspecialchars($_POST['address']) : $this -> _back('请填写详细地址');

	    		$data['area'] = isset($_POST['area']) ? htmlspecialchars($_POST['area']) : $this -> _back('请填写所属地区');

	    		$data['updated_at'] = time();

	    		$data['is_default'] = isset($_POST['is_default']) ? 1 : 0;

	    		if ($data['is_default'] == 1)
	    		{
	    			$params = array(

	    				'table_name' => 'user_address',

	    				'where' => "user_id = {$_SESSION['Rongzi']['user']['uid']} AND is_del = 0 AND is_default = 1",

	    				'data' => array(

	    					'is_default' => 0,

	    					'updated_at' => time()
	    				)
	    			);

	    			$address_save = $this -> model -> my_save($params);
	    		}

	    		$params = array(

	    			'table_name' => 'user_address',

	    			'where' => "id = {$id}",

	    			'data' => $data
	    		);

	    		$user_address_save = $this -> model -> my_save($params);

	    		if ($user_address_save)
	    		{
	    			redirect(__APP__.'/Address/index', 0);
	    		}
	    		else
	    		{
	    			$this -> _back('保存失败 请稍后重试');
	    		}
	    	}

	    	$this -> assign('result', $result);

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
	    	$id = isset($_GET['id']) ? intval($_GET['id']) : $this -> _back('缺少必要参数');

	    	$data['is_del'] = 1;

	    	$data['updated_at'] = time();

	    	$params = array(

	    		'table_name' => "user_address",

	    		'where' => "is_del = 0 AND id = {$id} AND user_id = {$_SESSION['Rongzi']['user']['uid']}",

	    		'data' => $data
	    	);

	    	$address_save = $this -> model -> my_save($params);

	    	if ($address_save)
	    	{
	    		redirect(__APP__.'/Address/index', 0);
	    	}
	    	else
	    	{
	    		$this -> _back('删除失败 请重试');
	    	}
	    }

	    /**
		 * 默认
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function set_default()
	    {
	    	$id = isset($_GET['id']) ? intval($_GET['id']) : $this -> _back('缺少必要参数');

	    	$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : $this -> _back('缺少指定类型');

	    	$params = array(

	    		'table_name' => 'user_address',

	    		'where' => "id = {$id} AND is_del = 0 AND user_id = {$_SESSION['Rongzi']['user']['uid']}"
	    	);

	    	$address_find = $this -> model -> my_find($params);

	    	if (!$address_find)
	    	{
	    		$this -> _back('没有找到指定项');
	    	}

	    	if ($type == 'set')
	    	{
	    		$params = array(

	    			'table_name' => 'user_address',

	    			'where' => "user_id = {$_SESSION['Rongzi']['user']['uid']} AND is_del = 0 AND is_default = 1",

	    			'data' => array(

	    				'is_default' => 0,

	    				'updated_at' => time()
 	    			)
	    		);

	    		$address_save = $this -> model -> my_save($params);

	    		$data['is_default'] = 1;
	    	}
	    	elseif ($type == 'unset')
	    	{
	    		$data['is_default'] = 0;
	    	}
	    	else
	    	{
	    		$this -> _back('未指定的类型');
	    	}

    		$data['updated_at'] = time();

    		$params = array(

    			'table_name' => 'user_address',

    			'where' => "id = {$address_find['id']}",

    			'data' => $data
    		);

    		$address_save = $this -> model -> my_save($params);

    		if ($address_save)
    		{
    			redirect(__APP__.'/Address/index', 0);
    		}
    		else
    		{
    			$this -> _back('发生错误 请稍后重试');
    		}
	    }
	}
