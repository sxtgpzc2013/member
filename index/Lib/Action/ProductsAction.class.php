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

    		'where' => "is_del = 0 AND id NOT IN ('1','2','3','4','5','6')",

    		'order' => 'created_at desc'
    	);

    	$result = $this -> model -> order_select($params);

    	$this -> assign('result', $result);

    	$this -> display();
    }

    /**
	 * 加入购物车
	 *
	 * 参数描述：
	 *
	 *
	 *
	 * 返回值：
	 *
	 */
    public function join_cart()
    {
    	$id = isset($_GET['id']) ? intval($_GET['id']) : $this -> _back('非法操作');

    	//查询这个商品
    	$params = array(

    		'table_name' => 'products',

    		'where' => "id = {$id} AND surplus > 0 AND status = 0 AND is_del = 0"
    	);

    	$product_find = $this -> model -> my_find($params);

    	if (!$product_find)
    	{
    		$this -> _back('没有找到相关商品');
    	}

    	//校验是否已存在购物车
    	if (isset($_SESSION['Rongzi']['cart'][$id]))
    	{
    		$_SESSION['Rongzi']['cart'][$id]['count'] += 1;
    	}
    	else
    	{
    		$_SESSION['Rongzi']['cart'][$id] = array(

    			'id' => $id,

    			'count' => 1,

    			'name' => $product_find['name'],

    			'products_code' => $product_find['products_code']
    		);
    	}

    	redirect(__APP__.'/Carts/index', 0);
    }
}
