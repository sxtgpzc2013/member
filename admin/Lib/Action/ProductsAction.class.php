<?php if (!defined('THINK_PATH')) exit();

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

class ProductsAction extends CommonAction {

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

		$this -> model = D('Products');
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

    		'table_name' => 'products',

    		'where' => "is_del = 0",

    		'order' => 'created_at desc'
    	);

    	$result = $this -> model -> order_select($params);

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
			$data['name'] = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : $this -> _back('商品名称不能为空');

			//logo
			$logo = $this -> _upload_pic('products');

			if ($logo['status'] == 1)
			{
				$data['logo'] = $logo['msg'];
			}
			elseif ($logo['status'] == 0)
			{
				$this -> _back($logo['msg']);
			}

			//这里缺少logo
			$data['content'] = isset($_POST['content']) ? $_POST['content'] : $this -> _back('商品详情不能为空');

			$data['rprice'] = isset($_POST['rprice']) ? floatval($_POST['rprice']) : $this -> _back('请填写戎子盾');

			$data['jprice'] = isset($_POST['jprice']) ? floatval($_POST['jprice']) : $this -> _back('请填写奖金币');

			$data['products_code'] = isset($_POST['products_code']) ? htmlspecialchars($_POST['products_code']) : $this -> _back('商品编号不能为空');

			//查询是否重复
			$params = array(

				'table_name' => 'products',

				'where' => "products_code = {$data['products_code']} AND is_del = 0"
			);

			$product_find = $this -> model -> my_find($params);

			if ($product_find)
			{
				$this -> _back('重复的商品编号');
			}

			$data['surplus'] = isset($_POST['surplus']) ? intval($_POST['surplus']) : $this -> _back('余量不能为空');

			$data['sell_count'] = 0;

			$data['status'] = isset($_POST['status']) && intval($_POST['status']) == 0 ? 0 : 1;

			$data['created_at'] = time();

			$data['updated_at'] = time();

			$data['is_del'] = 0;

			$params = array(

				'table_name' => 'products',

				'data' => $data
			);

			$product_add = $this -> model -> my_add($params);

			if ($product_add)
			{
				redirect(__APP__.'/Products/index', 0);
			}
			else
			{
				$this -> _back('添加失败 请重试');
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
		$id = isset($_POST['id']) ? intval($_POST['id']) : intval($_GET['id']);

		if (!$id)
		{
			$this -> _back('缺少必要参数');
		}

		$params = array(

			'table_name' => 'products',

			'where' => "id = {$id} AND is_del = 0"
		);

		$result = $this -> model -> my_find($params);

		if (!$result)
		{
			$this -> _back('没有找到该商品');
		}

		$form_key = htmlspecialchars($_POST['form_key']);

		if ($form_key == 'yes')
		{
			$data['name'] = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : $this -> _back('商品名称不能为空');

			$logo = $this -> _upload_pic('products');

			if ($logo['status'] == 1)
			{
				$data['logo'] = $logo['msg'];
			}

			//这里缺少logo
			$data['content'] = isset($_POST['content']) ? $_POST['content'] : $this -> _back('商品详情不能为空');

			$data['rprice'] = isset($_POST['rprice']) ? floatval($_POST['rprice']) : $this -> _back('请填写戎子盾');

			$data['jprice'] = isset($_POST['jprice']) ? floatval($_POST['jprice']) : $this -> _back('请填写奖金币');

			$data['products_code'] = isset($_POST['products_code']) ? htmlspecialchars($_POST['products_code']) : $this -> _back('商品编号不能为空');

			//查询是否重复
			$params = array(

				'table_name' => 'products',

				'where' => "products_code = {$data['products_code']} AND is_del = 0"
			);

			$product_find = $this -> model -> my_find($params);

			if ($product_find && $data['products_code'] != $result['products_code'])
			{
				$this -> _back('重复的商品编号');
			}

			$data['surplus'] = isset($_POST['surplus']) ? intval($_POST['surplus']) : $this -> _back('余量不能为空');

			$data['status'] = isset($_POST['status']) && intval($_POST['status']) == 0 ? 0 : 1;

			$data['updated_at'] = time();

			$params = array(

				'table_name' => 'products',

				'where' => "id = {$id}",

				'data' => $data
			);

			$product_save = $this -> model -> my_save($params);

			if ($product_save)
			{
				redirect(__APP__.'/Products/index', 0);
			}
			else
			{
				$this -> _back('保存失败 请重试');
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

			'table_name' => 'products',

			'where' => "id = {$id}",

			'data' => $data
		);

		$product_save = $this -> model -> my_save($params);

		if ($product_save)
		{
			redirect(__APP__.'/Products/index', 0);
		}
		else
		{
			$this -> _back('删除失败 请稍后重试');
		}
	}
}
