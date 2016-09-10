<?php if (!defined('THINK_PATH')) exit();//判断是否加载thinkphp,如果否则退出
/*********文件描述*********
 * @last update 2014-08-11
 * @alter 
 * @version 1.0.0
 *
 * 功能简介：商户后台销售型内容管理控制器类
 * @author qbx
 * @copyright 
 * @time 2014-08-11
 * @version 1.0.0 
 */
	class ProductSortsAction extends CommonAction {

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

			$this -> model = D('ProductSorts');
		}

	    /**
		 * 展示型首页
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
	    	$result['menu_id'] = intval($_GET['menu_id']);

	    	//查询对应展示型信息
	    	$params = array(

	    		'table_name' => 'product_sorts',

	    		'where' => "is_del = 0 AND corp_id = ".CORP_ID.""
	    	);

	    	$data = $this -> model -> order_select($params);

	    	$result['product_sorts'] = $data['result'];

	    	$result['page'] = $data['page'];

	    	$this -> assign('result', $result);

	    	$this -> display();
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
	    	$form_key = htmlspecialchars($_POST['form_key']);

	    	if ($form_key == 'yes')
	    	{
	    		//数据包
	    		$data['corp_id'] = CORP_ID;

	    		$data['sor_name'] = $this -> _is_null(htmlspecialchars($_POST['name']), '请输入名称');
	    		$data['created_at'] = time();

	    		$data['updated_at'] = time();

	    		$data['is_del'] = 0;

	    		//写入数据库
	    		$params = array(

	    			'table_name' => 'product_sorts',

	    			'data' => $data
	    		);

	    		$info_add = $this -> model -> my_add($params);

	    		if ($info_add)
	    		{
	    			redirect(__APP__.'/ProductSorts/index', 0);
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
	    	

	    	$id = intval($_POST['id']) ? intval($_POST['id']) : intval($_GET['id']);

	    	//查询这个展示信息
	    	$params = array(

	    		'table_name' => 'product_sorts',

	    		'where' => "id = {$id} AND is_del = 0"
	    	);

	    	$result['info_find'] = $this -> model -> my_find($params);

	    	$form_key = htmlspecialchars($_POST['form_key']);

	    	if ($form_key == 'yes')
	    	{
	    		//数据包
				$data['sor_name'] = $this -> _is_null(htmlspecialchars($_POST['name']), '请输入名称');

	    		

	    		$data['updated_at'] = time();

	    		//写入数据库
	    		$params = array(

	    			'table_name' => 'product_sorts',

	    			'where' => "id = {$id} AND is_del = 0",

	    			'data' => $data
	    		);

	    		$info_save = $this -> model -> my_save($params);

	    		if ($info_save == 1)
	    		{
	    			redirect(__APP__.'/ProductSorts/index', 0);
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
	    	$menu_id = intval($_GET['menu_id']);

	    	$id = intval($_GET['id']);

	    	//数据包
	    	$data['is_del'] = 1;

	    	$data['updated_at'] = time();

	    	//写入数据库
	    	$params = array(

	    		'table_name' => 'products',

	    		'where' => "id = {$id}",

	    		'data' => $data
	    	);

	    	$info_save = $this -> model -> my_save($params);

	    	if ($info_save == 1)
	    	{
	    		redirect(__APP__.'/Products/index/menu_id/'.$menu_id, 0);
	    	}
	    	else
	    	{
	    		$this -> _back('删除失败，请重试。');
	    	}
	    }
	}