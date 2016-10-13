<?php if (!defined('THINK_PATH')) exit();//判断是否加载thinkphp,如果否则退出
/*********文件描述*********
 * @last update  
 * @alter
 * @version 1.0.0
 *
 * 功能简介： 
 * @author qbx
 * @copyright
 * @time  
 * @version 1.0.0
 */
	class DemoAction extends CommonAction {

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

			$this -> model = D('Demo');
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


	    	//查询对应展示型信息
	    	// $params = array(
			//
	    	// 	'table_name' => 'products',
			//
	    	// 	'where' => "is_del = 0 AND corp_id = ".CORP_ID.""
	    	// );
			//
	    	// $data = $this -> model -> order_select($params);
			//
	    	// $result['products'] = $data['result'];
			//
	    	// $result['page'] = $data['page'];
	    	//
	    	// $this -> assign('result', $result);

	    	$this -> display();
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
	    public function product_index()
	    {
	    	//获取商品分类
	    	$params = array(

	    		'table_name' => 'product_sorts',

	    		'where' => "is_del = 0"
	    	);

	    	$result['product_sor'] =  $this -> model -> easy_select($params);

	    	//查询对应展示型信息
	    	$params = array(

	    		'table_name' => 'products',

	    		'where' => "is_del = 0"
	    	);

	    	$data = $this -> model -> order_select($params);

	    	$result['products'] = $data['result'];

	    	foreach ($result['products'] as $key => $value) {

	    		# 查看是否已添加
	    		$params = array(

		    		'table_name' => 'corp_product_link',

		    		'where' => "is_del = 0 AND corp_id = ".CORP_ID." AND product_id = {$value['id']}"
	    		);

	    		$link_result = $this -> model -> my_find($params);

	    		if ($link_result) {
	    			$result['products'][$key]['is_add'] = 'yes';
	    		}else{
	    			$result['products'][$key]['is_add'] = 'no';
	    		}


	    	}

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
	    	//获取商户的商品分类
	    	$params = array(

	    		'table_name' => 'product_sorts',

	    		'where' => "is_del = 0"
	    	);

	    	$result['product_sor'] =  $this -> model -> easy_select($params);


	    	$form_key = htmlspecialchars($_POST['form_key']);

	    	if ($form_key == 'yes')
	    	{

	    		//查看分类是否重复
	    		$params = array(

	    			'table_name' => 'products',

	    			'where' => "name = '{$_POST['name']}' AND is_del = 0"
	    		);

	    		$info_select = $this -> model -> my_find($params);


	    		if ($info_select)
	    		{
	    			$this -> _back('名称已存在，跪求想好再填。');
	    		}
	    		else
	    		{

	    		}
	    		//数据包
	    		$data['name'] = $this -> _is_null(htmlspecialchars($_POST['name']), '请输入名称');

	    		//$data['sor_id'] = $this -> _is_null(htmlspecialchars($_POST['sor_id']), '请选择分类');

	    		$data['price'] = $this -> _is_null(htmlspecialchars($_POST['price']), '请输入价格');

	    		$logo = $this -> _upload_pic('products');

	    		if ($logo['status'] == 1)
	    		{
	    			$data['logo'] = $logo['msg'];
	    		}
	    		elseif ($logo['status'] == 0)
	    		{
	    			$this -> _back($logo['msg']);
	    		}


	    		$data['corp_id'] = $_SESSION['OftenGo']['user']['id'];

	    		$data['notice'] = htmlspecialchars($_POST['notice']);

		 		$data['products_code'] = htmlspecialchars($_POST['products_code']);

	    		$data['content'] = htmlspecialchars($_POST['content']);

	    		$data['products_keys'] = htmlspecialchars($_POST['products_keys']);

	    		$data['is_recommend'] = htmlspecialchars($_POST['is_recommend']);

	    		//预留状态值
	    		$data['status'] = 0;

	    		$data['created_at'] = time();

	    		$data['updated_at'] = time();

	    		$data['is_del'] = 0;
	    		//写入数据库
	    		$params = array(

	    			'table_name' => 'products',

	    			'data' => $data
	    		);

	    		$info_add = $this -> model -> my_add($params);

	    		if ($info_add)
	    		{
	    			redirect(__APP__.'/Products/index', 0);
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

	    	//获取商品分类
	    	$params = array(

	    		'table_name' => 'product_sorts',

	    		'where' => "is_del = 0"
	    	);

	    	$result['product_sor'] =  $this -> model -> easy_select($params);

	    	//查询这个展示信息
	    	$params = array(

	    		'table_name' => 'products',

	    		'where' => "id = {$id} AND is_del = 0"
	    	);

	    	$result['info_find'] = $this -> model -> my_find($params);

	    	$form_key = htmlspecialchars($_POST['form_key']);

	    	if ($form_key == 'yes')
	    	{

	    		//查看分类是否重复
	    		$params = array(

	    			'table_name' => 'products',

	    			'where' => "name = '{$_POST['name']}' AND is_del = 0"
	    		);

	    		$info_select = $this -> model -> my_find($params);

	    		$params = array(

	    			'table_name' => 'products',

	    			'where' => "id = '{$id}' AND is_del = 0"
	    		);

	    		$my_find = $this -> model -> my_find($params);

	    		if ($info_select)
	    		{

	    			if($_POST['name']==$my_find['name']){

	    			}else{
	    				$this -> _back('名称已存在，跪求想好再填。');
	    			}

	    		}
	    		else
	    		{

	    		}
	    		//数据包
				$data['name'] = $this -> _is_null(htmlspecialchars($_POST['name']), '请输入名称');

				//$data['sor_id'] = $this -> _is_null(htmlspecialchars($_POST['sor_id']), '请选择分类');

	    		$logo = $this -> _upload_pic('products');

	    		if ($logo['status'] == 1)
	    		{
	    			$data['logo'] = $logo['msg'];
	    		}

	    		$data['notice'] = htmlspecialchars($_POST['notice']);

	    		$data['content'] = htmlspecialchars($_POST['content']);

	    		$data['price'] = $this -> _is_null(htmlspecialchars($_POST['price']), '请输入价格');


	    		$data['products_code'] = htmlspecialchars($_POST['products_code']);

	    		//预留详情页样式标志
	    		$data['products_keys'] = htmlspecialchars($_POST['products_keys']);

	    		$data['is_recommend'] = htmlspecialchars($_POST['is_recommend']);

	    		//预留状态值
	    		$data['status'] = 0;

	    		$data['updated_at'] = time();

	    		//写入数据库
	    		$params = array(

	    			'table_name' => 'products',

	    			'where' => "id = {$id} AND is_del = 0",

	    			'data' => $data
	    		);

	    		$info_save = $this -> model -> my_save($params);

	    		if ($info_save == 1)
	    		{
	    			redirect(__APP__.'/Products/index', 0);
	    		}
	    		else
	    		{
	    			$this -> _back('保存失败，请重试。');
	    		}
	    	}

	    	//查询这个展示信息
	    	$id = intval($_GET['id']);

	    	$params = array(

	    		'table_name' => 'logos',

	    		'where' => "pid = {$id} AND is_del = 0 AND type_str = 'products'"
	    	);

	    	$result['info_logos'] = $this -> model -> easy_select($params);

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
